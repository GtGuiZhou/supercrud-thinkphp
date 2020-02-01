<?php
// 应用公共文件

use app\model\AdminRoleModel;
use think\Model;

function fastValidate($data, $rule){

}


/*
 * 下划线转驼峰
 */
function convertUnderline($str)
{
    $str = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){
        return strtoupper($matches[2]);
    },$str);
    return $str;
}

/*
 * 驼峰转下划线
 */
function humpToLine($str){
    $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
        return '_'.strtolower($matches[0]);
    },$str);
    return $str;
}


function build_model_tree(Model $model,$childrenRelationName)
{
    foreach ($model->$childrenRelationName as &$child){
        $child = build_model_tree($child,$childrenRelationName);
    }
    return $model;
}

function model_tree_to_collection($root){

}