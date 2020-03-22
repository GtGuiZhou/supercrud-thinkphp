<?php


namespace app\controller\api;


use app\BaseController;
use app\exceptions\CheckException;
use app\model\FileModel;
use think\facade\Cache;
use think\facade\Filesystem;

class File extends BaseController
{

    

    public function upload()
    {
        $file = $this->request->file('file');
        if (!Cache::has(FileModel::cacheKey($file->md5())) && !FileModel::where('md5',$file->md5())->find()){
            $url = Filesystem::disk('local')->putFIle('upload', $file, 'md5');
            Cache::set(FileModel::cacheKey($file->md5()),$url);
            FileModel::create([
                'md5' => $file->md5(),
                'url' => $url,
                'local_url' => $url
            ]);
        }

        return '/api/file/'.$file->md5();
    }

    public function read($md5)
    {
        if (!$url = Cache::get(FileModel::cacheKey($md5))){
            if ($model = FileModel::where('md5',$md5)->find()){
                $url = $model['url'];
            } else {
                throw new CheckException('文件不存在');
            }
        }

        // 检测是不是本地存储的文件
        if (strpos($url,'http') !== 0)
            $url = config('filesystem.disks.local.root').DIRECTORY_SEPARATOR.$url;
        return download($url);
    }

    public function uploadMulti()
    {

        $files = $this->request->file('file');
        $data = [];
        foreach ($files as $file) {
            if (!Cache::has(FileModel::cacheKey($file->md5())) && FileModel::where('md5', $file->md5())->find()) {
                $url = Filesystem::disk('local')->putFIle('upload', $file, 'md5');
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

        $result = array_map(function ($item){
            return '/api/file/'.$item['md5'];
        },$data);

        return json($result);
    }
}