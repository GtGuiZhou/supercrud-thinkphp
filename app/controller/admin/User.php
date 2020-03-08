<?php


namespace app\controller\admin;


use app\AdminController;
use app\model\UserModel;

class User extends AdminController
{

    protected $searchField = 'username';
    protected function initialize()
    {
        $this->model = new UserModel();
    }
}