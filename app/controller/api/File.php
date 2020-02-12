<?php


namespace app\controller\api;


use app\BaseController;
use think\facade\Filesystem;

class File extends BaseController
{


    public function upload()
    {
        $file = $this->request->file('file');
        $result = Filesystem::disk('public')->putFIle('topic', $file, 'md5');
        $prefix = config('filesystem.disks.public.url');
        return json($prefix.'/'.$result);
    }

    public function uploadMulti()
    {
        $files = $this->request->file('file');
        $result = [];
        $prefix = config('filesystem.disks.public.url');
        foreach ($files as $file) {
            $result[] = $prefix.'/'.Filesystem::disk('public')->putFIle('topic', $file, 'md5');
        }

        return json($result);
    }
}