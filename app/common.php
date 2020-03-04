<?php
// 应用公共文件

use app\model\AdminRoleModel;
use think\Model;


if (!function_exists('convertUnderline')) {
    /*
 * 下划线转驼峰
 */
    function convertUnderline($str)
    {
        $str = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
            return strtoupper($matches[2]);
        }, $str);
        return $str;
    }
}


if (!function_exists('humpToLine')) {
    /*
     * 驼峰转下划线
     */
    function humpToLine($str)
    {
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return $str;
    }
}


if (!function_exists('array_to_tree')) {
    function array_to_tree(array $arr, $rootNodeCase = 0, $pkField = 'id', $parentField = 'pid', $childrenFiled = 'children')
    {
        foreach ($arr as $node) {
            $node[$childrenFiled] = array_filter($arr, function ($item) use ($node, $pkField, $parentField) {
                return $item[$parentField] == $node[$pkField];
            });
        }
        $result = [];
        foreach ($arr as $node){
            if ($node[$parentField] == $rootNodeCase){
                $result[] = $node;
            }
        }

        return $result;
    }
}
