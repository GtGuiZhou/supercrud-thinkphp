<?php


namespace app\controller\admin;


use app\AdminController;
use app\exceptions\ControllerException;
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

    public function index()
    {
        $roleList = $this->admin->role->tree()->toArray();
        // 不输出管理员自身
        $roleList = array_filter($roleList,function ($role){ return $role['id'] != $this->admin->role_id;});
        $childrenRoleList = array_column($roleList,'id');
        $childrenRoleList = array_unique($childrenRoleList);
        $page = $this->request->get('page',1);
        $size = $this->request->get('size',10);
        $query = function ($query) use ($childrenRoleList){
            $search = $this->request->get('search');
            $query->where('role_id','in',$childrenRoleList);
            if ($search){
                $query->whereLike('username',"%$search%");
            }
        };
        $result = AdminModel::where($query)
            ->with('role')
            ->page($page,$size)->select();
        $total = AdminModel::where($query)->count();
        return json(['data' => $result,'total' => $total]);

    }

    public function insert()
    {
        $this->model = new AdminModel();
        $this->admin->validateChildrenRole($this->request->post('role_id'));
        return parent::insert();
    }

    public function update($id)
    {
        $this->admin->validateChildrenRole($id);
        $this->admin->validateChildrenRole($this->request->post('role_id'));
        $this->model = new AdminModel();
        return parent::update($id);
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

    public function delete($id)
    {
        $model = AdminModel::find($id);
        if (!$model){
            throw new ControllerException('删除数据不存在');
        }
        $this->admin->validateChildrenAdmin($model);
        $model->delete();
    }

}