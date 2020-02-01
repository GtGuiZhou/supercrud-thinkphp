<?php


namespace app\controller\admin;


use app\AdminController;
use app\model\AdminModel;
use app\model\AdminRuleModel;

class ChildrenAdmin extends AdminController
{

    protected function initialize()
    {
        if ($id = $this->request->get('id')) {
            $admin = AdminModel::findOrFail($id);
            $this->admin->validateChildrenAdmin($admin);
            $this->model = $admin;
        }
    }

    public function insert()
    {
        $this->model = new AdminModel();
        $this->admin->validateChildrenRole($this->request->post('roleId'));
        return parent::insert();
    }

    public function rulesTree($id)
    {

        $list = $this->model->role->rules->toArray();
        $result = AdminRuleModel::transformTree($list);
        return json($result);

    }

    public function rulesList($id)
    {
        $list = $this->model->role->rules->toArray();
        return json($list);
    }

}