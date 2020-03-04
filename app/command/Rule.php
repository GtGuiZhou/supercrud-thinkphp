<?php
declare (strict_types = 1);

namespace app\command;

use app\model\AdminRoleRuleModel;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Rule extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('rule')
            ->addArgument('rule')
            ->addArgument('controllerName')
            ->setDescription('the rule command');
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
        $rule = $input->getArgument('rule');
    	$controllerName = $input->getArgument('controllerName');
        $menu = AdminRoleRuleModel::create(['rule' => $rule,'name' => $controllerName,'is_menu' => 'yes']);
        AdminRoleRuleModel::create(['rule' => $rule.'/delete','name' => '删除','pid' => $menu->id]);
        AdminRoleRuleModel::create(['rule' => $rule.'/update','name' => '更新','pid' => $menu->id]);
        AdminRoleRuleModel::create(['rule' => $rule.'/insert','name' => '新增','pid' => $menu->id]);
        AdminRoleRuleModel::create(['rule' => $rule.'/index','name' => '查看','pid' => $menu->id]);

    }
}
