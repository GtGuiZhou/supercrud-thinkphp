<?php


namespace app\controller\api;


use app\BaseController;
use app\exceptions\CheckException;
use app\model\FileModel;
use think\facade\Cache;
use think\facade\Filesystem;

class File extends BaseController
{

    private function cacheKey($md5){
        return 'file:'.$md5;
    }

    public function upload()
    {
        $file = $this->request->file('file');
        if (!Cache::has($this->cacheKey($file->md5())) && !FileModel::where('md5',$file->md5())->find()){
            $url = Filesystem::disk('local')->putFIle('upload', $file, 'md5');
            Cache::set($this->cacheKey($file->md5()),$url);
            FileModel::create([
                'md5' => $file->md5(),
                'url' => $url
            ]);
        }

        return '/api/file/'.$file->md5();
    }

    public function read($md5)
    {
        if (!$url = Cache::get($this->cacheKey($md5))){
            if ($model = FileModel::where('md5',$md5)->find()){
                $url = $model['url'];
            } else {
                throw new CheckException('文件不存在');
            }
        }

        return download(config('filesystem.disks.local.root').DIRECTORY_SEPARATOR.$url);
    }

    public function uploadMulti()
    {

        $files = $this->request->file('file');
        $data = [];
        foreach ($files as $file) {
            if (!Cache::has($this->cacheKey($file->md5())) && FileModel::where('md5', $file->md5())->find()) {
                $url = Filesystem::disk('local')->putFIle('upload', $file, 'md5');
                Cache::set($this->cacheKey($file->md5()), $url);
                $data[] = [
                    'md5' => $file->md5(),
                    'url' => $url
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