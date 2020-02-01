<?php
declare (strict_types = 1);

namespace app\model;

use app\exceptions\ModelException;
use think\Model;
use think\model\Collection;

/**
 * @mixin think\Model
 */
class AdminRuleModel extends Model
{
    protected $table = 'admin_rule';


    public function setRuleAttr($val)
    {
        return strtolower($val);
    }


    /**
     *
     * @param array $list 规则集合
     */
    public static function transformTree(array $list)
    {
        $result = [];
        if (count($list) > 0) {
            // 加载父级，转换为树型结构
            $parentArray = [];
            foreach ($list as $item) {
                $parentArray[] = $item['pid'];
            }
            $parentArray = array_unique($parentArray);

            $list += AdminModel::select($parentArray)->toArray();

            foreach ($list as &$item) {
                $item['children'] = array_filter($list, function ($rule) use ($item) {
                    return $rule['pid'] == $item['id'];
                });
            }


            foreach ($list as &$item) {
                if ($item['pid'] == 0)
                    $result[] = $item;
            }

            return $result;
        }
    }
}
