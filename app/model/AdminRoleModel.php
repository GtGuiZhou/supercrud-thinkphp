<?php
declare (strict_types=1);

namespace app\model;

use app\exceptions\ModelException;
use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class AdminRoleModel extends Model
{
    const ROOT_ROLE_PID = 0;

    protected $table = 'admin_role';

    public function rule()
    {
        return $this->hasMany(AdminRoleRuleModel::class, 'role_id', 'id');
    }

    public function menu()
    {
        return $this->hasMany(AdminRoleMenuModel::class,'role_id','id');
    }


}
