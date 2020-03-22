<?php


namespace app\model;


use think\facade\Cache;
use think\Model;

class FileModel extends Model
{
    protected $table = 'file';


    public static function onBeforeUpdate(Model $model)
    {
        // 更新数据记录的时候记得更新缓存
        Cache::set(self::cacheKey($model['md5']),$model['url']);
    }

    public static function cacheKey($md5){
        return 'file:'.$md5;
    }


}