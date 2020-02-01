<?php


namespace app\controller\admin;


use app\AdminController;
use app\model\AdminRoleModel;
use app\model\AdminRuleModel;

class ChildrenRole extends AdminController
{


    public function index()
    {
        $list = $this->admin->role->childrenTree()->echo();

        return json($list);
    }

    public function insert()
    {
        $data = $this->request->post();
        $this->admin->validateChildrenRole(AdminRoleModel::findOrFail($data['pid']));
        $model = AdminRoleModel::create($data);

        return json($model);
    }

    public function update($id)
    {
        $data = $this->request->post();
        $this->admin->validateChildrenRole($id);
        $this->admin->validateChildrenRole($data['pid']);
        $updateRole = AdminRoleModel::findOrFail($id);
        $updateRole->save($data);
        return json($updateRole);
    }

    public function delete($id)
    {
        $this->admin->validateChildrenRole($id);
        $deleteModel = AdminRoleModel::findOrFail($id);
        $deleteModel->delete();
    }


    /**
     * 角色关联规则
     * @param $roleId
     * @param $ruleId
     * @throws \app\exceptions\ModelException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function relationRule($roleId,$ruleId)
    {
        $this->admin->validateChildrenRole($roleId);
        $this->admin->validateHaveRule($ruleId);
        $role = AdminRoleModel::findOrFail($roleId);
        $role->attach($ruleId);
    }

    public function releaseRule($roleId,$ruleId)
    {
        $this->admin->validateChildrenRole($roleId);
        $role = AdminRoleModel::findOrFail($roleId);
        $role->detach($ruleId);
    }

}