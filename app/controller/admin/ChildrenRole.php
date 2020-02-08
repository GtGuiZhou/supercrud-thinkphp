<?php


namespace app\controller\admin;


use app\AdminController;
use app\exceptions\ControllerException;
use app\model\AdminRoleModel;
use app\model\AdminRuleModel;
use think\facade\Db;

class ChildrenRole extends AdminController
{

    protected $noNeedRule = ['insert','delete','update','index'];


    public function index()
    {
        $tree = $this->admin->role->tree()->echo();

        return json($tree);
    }

    public function insert()
    {
        $this->validate(input(),[
           'name|角色名称' => 'require|length:1,50',
           'pid|父角色' => 'require|number',
           'rules|规则' => 'require|array'
        ]);
        $data = $this->request->post();
        $this->admin->validateRoleOverflow(AdminRoleModel::findOrFail($data['pid']));
        $model = Db::transaction(function () use ($data){
            $model = AdminRoleModel::create($data);
            $this->relationRules($model,$data['rules']);
            return $model;
        });

        return json($model);
    }

    public function update($id)
    {
        $data = $this->request->post();
        // 更新父角色不能是超过当前管理员角色的
        $this->admin->validateChildrenRole($id);
        $this->admin->validateRoleOverflow($data['pid']);
        $updateRole = AdminRoleModel::findOrFail($id);
        // 不能将父角色设置为自己
        if ($updateRole->id == $data['pid']){
            throw new ControllerException('不能将自己设定为父角色');
        }
        // 不能将父角色设定为子角色
        if ($updateRole->isChildren($data['pid'])){
            throw new ControllerException('不能将子角色设定为父角色');
        }
        Db::transaction(function () use ($updateRole,$data){
            // 保存角色信息
            $updateRole->save($data);
            $this->relationRules($updateRole,$data['rules']);
            // 删除后代多余的规则
            $updateRole->deleteChildRoleRuleOverflow();
        });

        return json($updateRole);
    }

    public function delete($id)
    {
        $this->admin->validateChildrenRole($id);
        $deleteModel = AdminRoleModel::findOrFail($id);
        $deleteModel->delete();
    }


    public function rulesList($roleId)
    {
        $this->admin->validateChildrenRole($roleId);
        $role = AdminRoleModel::findOrFail($roleId);
        return json($role->rules);
    }

    public function rulesTree($roleId)
    {
        $this->admin->validateChildrenRole($roleId);
        $role = AdminRoleModel::findOrFail($roleId);
        return json(AdminRuleModel::transformTree($role->rules->toArray()));
    }

    private function relationRules(AdminRoleModel $role,$rules)
    {
        $this->admin->validateChildrenRole($role->id);
        $existRuleCollection = collect($role->rules);

        foreach ($rules as $rule){
            // 检测他的父亲有没有这个规则
            $role->parentRule->validateHaveRule($rule);
            // 不重复添加规则
            if ($existRuleCollection->where('rule_id',$rule)->count() < 1){
                $role->rules()->attach($rule);
            }
        }
    }
}