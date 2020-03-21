<?php


namespace app\model;


use think\Model;

abstract class SessionUserModel extends Model
{
    /**
     * 获取当前用户模型的更新时间
     * @return int
     */
    abstract public function getUpdateTime(): int;
}