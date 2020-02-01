<?php


namespace app\controller\api;


use app\BaseController;
use think\facade\Filesystem;

class File extends BaseController
{
    public function upload()
    {
        $files = $this->request->post('file');
        $files = is_array($files) ? $files : [$files];
        $result = [];
        foreach ($files as $file) {
            $result[] = Filesystem::disk('public')->putFIle('topic', $file, 'md5');
        }

        return $result;
    }
}