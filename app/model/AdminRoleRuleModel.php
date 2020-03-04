<?php
declare (strict_types=1);

namespace app\model;

use app\exceptions\ModelException;
use think\Model;

/**
 * @mixin think\Model
 */
class AdminRoleRuleModel extends Model
{
    protected $table = 'admin_role_rule';


}
