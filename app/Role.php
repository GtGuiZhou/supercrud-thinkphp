<?php


namespace app;


use app\model\AdminRoleModel;

class Role extends AdminController
{
    protected function initialize()
    {
        $this->model = new AdminRoleModel();
    }
}