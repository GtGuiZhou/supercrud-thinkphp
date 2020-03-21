<?php
declare (strict_types = 1);

namespace app\model;

use app\exceptions\CheckException;
use think\Model;

/**
 * @mixin think\Model
 */
class AdminUserModel extends SessionUserModel
{
    //
    protected $table = 'admin';

    protected $childrenRoleTree = null;

    protected $readonly = ['username'];


    public static function onBeforeInsert(Model $model)
    {
        // 检测用户是否已经在系统里面了
        if (self::where('username',$model->username)->find()){
            throw new CheckException('该用户已存在');
        }
    }



    /**
     * 给密码加密
     * @param $val
     * @return string
     */
    public function setPasswordAttr($val)
    {
        // 代表没有修改过秘钥
        if ($val == $this->password){
            return  $val;
        }
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

    public function loginRecord()
    {
        return $this->hasMany(AdminLoginRecordModel::class,'admin_id','id');
    }

    public function isRootRole()
    {
        return $this->root == 'yes';
    }

    /**
     * 查询当前管理员是否含有
     * @param string $rule
     * @return bool
     */
    public function haveRule($rule)
    {
        return $this->isRootRole() || $this->role->rule()->where('rule',$rule)->find();
    }

    /**
     * @inheritDoc
     */
    public function getUpdateTime(): int
    {
        return strtotime($this->create_time);
    }
}
