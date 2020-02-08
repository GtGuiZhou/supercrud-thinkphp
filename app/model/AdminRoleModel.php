<?php
declare (strict_types=1);

namespace app\model;

use app\exceptions\ModelException;
use think\Model;

/**
 * @mixin think\Model
 */
class AdminRoleModel extends Model
{
    const ROOT_ROLE_PID = 0;

    protected $table = 'admin_role';
    /**
     * @var ModelTree
     */
    private $tree;


    public function isRoot()
    {
        // 用父id来判断是不是超级管理员吧
        return $this->pid == self::ROOT_ROLE_PID;
    }

    public function rules()
    {
        return $this->belongsToMany(AdminRuleModel::class, 'admin_role_rule', 'rule_id', 'role_id');
    }


    public function parentRule()
    {
        return $this->belongsTo(self::class, 'pid', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'pid', 'id');
    }

    /**
     * 当前角色和他的后代组成的树
     * @return ModelTree
     */
    public function Tree()
    {
        if (!$this->tree)
            $this->tree = new ModelTree($this, 'children');
        return $this->tree;
    }

    /**
     * 是否是子角色
     * @param int $roleId
     * @return bool
     */
    public function isChildren($roleId)
    {
        if ($this->id === $roleId) return false;
        return $this->tree()->where('id', $roleId)->count() > 0;
    }

    public function getRulesAttr($val)
    {

        if ($this->isRoot()) {
            $val = AdminRuleModel::select();
        }

        return $val;
    }

    public function validateHaveRule($ruleId)
    {
        if (!$this->isRoot() && !$this->rules()->where('id', $ruleId)->count() < 0) {
            throw new ModelException('无权操作该规则');
        }
    }


    /**
     * 删除子角色中他们拥有而当前角色没有的规则
     */
    public function deleteChildRoleRuleOverflow()
    {
        // 获取所有后代和当前角色
        $childrenArray = $this->tree()->toArray();
        // 取出当前角色的规则
        $rulesId = $this->rules()->field('id')->select()->toArray();
        $rulesId = array_column($rulesId, 'id');
        foreach ($childrenArray as $children) {
            // 获取后代的规则
            $childrenRulesId = $children->rules()->field(['id'])->select()->toArray();
            $childrenRulesId = array_column($childrenRulesId, 'id');
            // 删掉多出来的规则
            $deleteRule = array_diff($rulesId, $childrenRulesId);
            if (count($deleteRule))
                $children->rules()->detach($deleteRule);
        }
    }

}
