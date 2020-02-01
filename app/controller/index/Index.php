<?php


namespace app\controller\index;


use app\BaseController;
use think\route\dispatch\Url;

class Index extends BaseController
{

    public   $noNeedLogin = ['index'];
    public function index()
    {
        var_dump($this->request->controller());

        var_dump($reflect->getDefaultProperties());
        var_dump(app_path());
        var_dump(app('http')->getName());


    }
}