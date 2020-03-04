<?php


namespace app\controller\admin;


use app\AdminController;
use app\model\AdminRoleModel;
use think\facade\Db;

class Role extends AdminController
{

    protected $searchField = 'name';
    protected $indexWith = ['menu','rule'];

    protected function initialize()
    {
        $this->model = new AdminRoleModel();
    }

    public function updateMenu($id)
    {
        $role = $this->model->findOrFail($id);
        $menu = $this->request->post('menu');
        Db::transaction(function () use ($role,$menu){
            $role->menu()->where('1=1')->delete();
            $role->menu()->saveAll($menu,false);
        });
    }

    public function updateRule($id)
    {
        $role = $this->model->findOrFail($id);
        $rule = $this->request->post('rule');
        Db::transaction(function () use ($role,$rule){
            $role->rule()->where('1=1')->delete();
            $role->rule()->saveAll($rule,false);
        });
    }
}