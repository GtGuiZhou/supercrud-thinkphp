<?php
declare (strict_types = 1);

namespace app\model;

use app\exceptions\ModelException;
use app\middleware\auth\AuthUserModelInterface;
use think\Model;

/**
 * @mixin think\Model
 */
class AdminModel extends Model implements AuthUserModelInterface
{
    //
    protected $table = 'admin';

    protected $childrenRoleTree = null;


    public static function onBeforeInsert(Model $model)
    {
        // 检测用户是否已经在系统里面了
        if (self::where('username',$model->username)->find()){
            throw new ModelException('该用户已存在');
        }
    }

    /**
     * 给密码加密
     * @param $val
     * @return string
     */
    public function setPasswordAttr($val)
    {
        return $this->encryptPassword(($val));
    }

    /**
     * 对比密码
     * @param $password
     * @return bool
     */
    public function contrastPassword($password)
    {
        return $this->encryptPassword($password) == $this->getAttr('password');
    }

    private function encryptPassword($password)
    {
        return md5($password);
    }

    public function role()
    {
        return $this->belongsTo(AdminRoleModel::class,'role_id','id');
    }

    /**
     * 是否为子管理员
     * @param AdminModel $admin
     * @return mixed
     */
    public function validateChildrenAdmin(AdminModel $admin)
    {
        if (!$this->isRoot() && !$this->role->isChildren($admin->role->id)){
            throw new ModelException('无权操作该管理员');
        }
    }



    /**
     * 验证是不是子角色
     * @param int $roleId
     * @throws ModelException
     */
    public function validateChildrenRole($roleId)
    {
        if (!$this->isRoot() && !$this->role->isChildren($roleId)){
            throw new ModelException('无权操作该角色');
        }
    }

    /**
     * 验证是否含有该规则
     * @param int $ruleId
     */
    public function validateHaveRule($ruleId)
    {
        if (!$this->isRoot() && !$this->role->rules()->where('id',$ruleId)->count() < 0){
            throw new ModelException('无权操作该规则');
        }
    }



    /**
     * @inheritDoc
     */
    public function haveRule($rule)
    {

        if (!$this->role)
            return false;

        $count = $this->role->rules()->where('rule',$rule)->count();

        return $count > 0;
    }

    /**
     * @inheritDoc
     */
    public function isRoot()
    {

        if (!$this->role) return false;
        return $this->role->isRoot();
    }
}