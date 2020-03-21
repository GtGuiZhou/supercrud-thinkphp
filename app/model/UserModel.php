<?php
declare (strict_types = 1);

namespace app\model;

use app\exceptions\CheckException;
use think\Model;

/**
 * @mixin Model
 */
class UserModel extends SessionUserModel
{
    use UserModelResourceRelationTrait;

    //
    protected $table = 'user';

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

    public function payOrder()
    {
        return $this->belongsToMany(PayOrderModel::class,'user_pay_order','out_trade_no','user_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfileModel::class,'user_id','id');
    }


    /**
     * @inheritDoc
     */
    public function getUpdateTime(): int
    {
        return strtotime($this->create_time);
    }
}
