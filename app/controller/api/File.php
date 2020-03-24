<?php


namespace app\controller\api;


use app\BaseController;
use app\exceptions\CheckException;
use app\model\FileModel;
use app\service\FileStoreService;
use think\App;
use think\facade\Cache;
use think\facade\Filesystem;

class File extends BaseController
{

    /**
     * @var FileStoreService
     */
    private $storeService;

    public function __construct(App $app)
    {
        $this->storeService = new FileStoreService(config('filesystem.servers', []));
        parent::__construct($app);
    }

    public function upload()
    {
        $file = $this->request->file('file');
        if (!Cache::has(FileModel::cacheKey($file->md5())) && !FileModel::where('md5', $file->md5())->find()) {
            $url = $this->storeService->save($file);
            Cache::set(FileModel::cacheKey($file->md5()), $url);
            FileModel::create([
                'md5' => $file->md5(),
                'url' => $url,
                'local_url' => $url
            ]);
        }

        return '/api/file/' . $file->md5();
    }

    public function read($md5)
    {
        $storeService = $this->storeService;
        $url = $storeService->getFileUrl($md5);
        // 检测是不是第三方存储
        if (strpos($url, 'http') === 0) {
            return redirect($url);
        }
        // 本地存储
        if ($path = $storeService->readLocal($url)) {
            return download($path, pathinfo($url, PATHINFO_BASENAME));
        }
        // 存储在其他服务器上
        if ($file = $storeService->readOtherServers($md5)) {
            return download($file, pathinfo($url, PATHINFO_BASENAME), true);
        }

        throw new CheckException('文件不存在');
    }

    /**
     * 用来实现分布式文件读取
     * @param $md5
     * @return \think\response\File|\think\response\Redirect
     * @throws CheckException
     */
    public function readLocal($md5)
    {
        $storeService = new FileStoreService(config('filesystem.servers', []));
        $url = $storeService->getFileUrl($md5);
        // 检测是不是第三方存储
        if (strpos($url, 'http') === 0) {
            return redirect($url);
        }
        // 本地存储
        if ($file = $storeService->getPath($url)) {
            return download($file, pathinfo($url, PATHINFO_BASENAME), true);
        }
        throw new CheckException('文件不存在');
    }

    public function uploadMulti()
    {
        $files = $this->request->file('file');
        $data = [];
        foreach ($files as $file) {
            if (!Cache::has(FileModel::cacheKey($file->md5())) && FileModel::where('md5', $file->md5())->find()) {
                $url = $this->storeService->save($file);
                Cache::set(FileModel::cacheKey($file->md5()), $url);
                $data[] = [
                    'md5' => $file->md5(),
                    'url' => $url,
                    'local_url' => $url
                ];
            }
        }
        if (count($data) > 0) {
            $model = new FileModel();
            $model->saveAll($data);
        }

        $result = array_map(function ($item) {
            return '/api/file/' . $item['md5'];
        }, $data);

        return json($result);
    }
}