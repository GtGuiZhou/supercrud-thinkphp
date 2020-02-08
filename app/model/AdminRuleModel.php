<?php
declare (strict_types=1);

namespace app\model;

use app\exceptions\ModelException;
use think\Model;

/**
 * @mixin think\Model
 */
class AdminRuleModel extends Model
{
    protected $table = 'admin_rule';


    public static function onBeforeDelete(Model $model)
    {
        if (count($model->children) > 0) {
            throw new ModelException('含有子规则,不能删除');
        }
    }

    public static function onBeforeWrite(Model $model)
    {
        if ($model->is_menu == 'no' && count($model->children) > 0) {
            throw new ModelException('含有子规则,不能修改菜单项为规则项');
        }
    }

    public function setRuleAttr($val)
    {
        return strtolower($val);
    }


    public function children()
    {
        return $this->hasMany(self::class, 'pid', 'id');
    }


    /**
     *
     * @param array $list 规则集合
     * @param bool $onlyMenu 仅包含菜单规则
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function transformTree(array $list, $onlyMenu = false)
    {
        $result = [];
        if (count($list) > 0) {
            // 加载所有节点的父级，转换为树型结构,防止绘制菜单的时候只有子节点,没有父节点
            $parentArray = array_map(function ($item) {
                return $item['pid'];
            }, $list);
            $curArray = array_map(function ($item) {
                return $item['id'];
            }, $list);
            $parentArray = array_filter($parentArray, function ($item) use ($curArray) {
                return !in_array($item, $curArray);
            });// 已经存在的不需要再次依靠下面的代码加载
            $parentArray = array_unique($parentArray);
            // 从数据库中获取父级详细信息
            $parentArray = AdminRuleModel::select($parentArray)->toArray();
            $list = array_merge($parentArray, $list);
            $list = array_filter($list, function ($item) use ($onlyMenu) {
                return !$onlyMenu || $item['is_menu'] == 'yes';
            });

            // 获取各个节点的子节点
            foreach ($list as &$item) {
                $item['children'] = [];
                foreach ($list as &$rule) {
                    if ($rule['pid'] == $item['id']) {
                        $item['children'][] = &$rule;
                    }
                }
                usort($item['children'],function ($a,$b){return $a['order'] > $b['order'];});
            }


            foreach ($list as &$item) {
                if ($item['pid'] == 0)
                    $result[] = $item;
            }
        }
        usort($result,function ($a,$b){return $a['order'] > $b['order'];});
        return $result;``
    }
}
