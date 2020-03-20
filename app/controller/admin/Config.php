<?php


namespace app\controller\admin;


use app\AdminController;

class Config extends AdminController
{

    public function index()
    {
        return config('site');
    }

    public function save()
    {
        $data = $this->request->post();
        cache('config:site',$data);
    }
}