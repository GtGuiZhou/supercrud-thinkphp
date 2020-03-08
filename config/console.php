<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'builder' => \app\command\Builder::class,
        'maar' => \app\command\MakeApiAccessRecord::class,
        'rule' => \app\command\Rule::class,
    ],
];
