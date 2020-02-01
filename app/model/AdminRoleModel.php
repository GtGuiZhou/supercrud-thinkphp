<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class AdminRoleModel extends Model
{
    const ROOT_ROLE_ID = 1;

    protected $table = 'admin_role';
    /**
     * @var ModelTree
     */
    private $childrenTree;


    public function isRoot()
    {
        return $this->getAttr('id') == self::ROOT_ROLE_ID;
    }

    public function rules()
    {
        return $this->belongsToMany(AdminRuleModel::class,'admin_role_rule','rule_id','role_id');
    }

    public function children()
    {
        return $this->hasMany(self::class,'pid','id');
    }

    public function childrenTree()
    {
        if (!$this->childrenTree)
            $this->childrenTree = new ModelTree($this,'children');
        return $this->childrenTree;
    }

    /**
     * 是否是子角色
     * @param int $roleId
     * @return bool
     */
    public function isChildren($roleId)
    {
        return $this->childrenTree()->where('id',$roleId)->count() > 0;
    }

    public function getRulesAttr($val)
    {

        if ($this->isRoot()){
            $val = AdminRuleModel::select();
        }

        return $val;
    }

}
