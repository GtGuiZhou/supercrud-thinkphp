<?php


namespace app\controller\admin;


use app\AdminController;

class Config extends AdminController
{

    public function index()
    {
        return [
            'site' => config('site'),
            'wechat' => config('wechat'),
            'cloudstore' => config('cloudstore'),
            'pay' => config('pay')
        ];
    }

    public function save()
    {
        $data = $this->request->post();
        cache('config',$data);
    }
}