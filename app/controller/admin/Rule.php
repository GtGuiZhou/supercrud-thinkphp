<?php
declare (strict_types=1);

namespace app\controller\admin;

use app\AdminController;
use app\model\AdminRoleRuleModel;

class Rule extends AdminController
{
    protected function initialize()
    {
        $this->model = new AdminRoleRuleModel();
    }

    public function index()
    {
        $list = app()->route->getRuleList();
        // 过滤属于出admin模块的路由，以及没有定义__GROUP__的路由
        $list = array_filter($list,function ($rule){
            return strpos($rule['rule'],'admin/') === 0 && isset($rule['option']['__GROUP__']);
        });
        // 分组,过滤掉不需要的属性
        $result = [];
        foreach ($list as $rule){
            $parent = $rule['option']['__GROUP__'];
            $result[$parent][] =['rule' => "{$rule['method']}-{$rule['rule']}",'name' => $rule['name']];
        }

        return $result;
    }


}
