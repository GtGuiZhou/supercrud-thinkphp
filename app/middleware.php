<?php
// 全局中间件定义文件
return [
    // api访问记录
    \app\middleware\ApiAccessRecord::class,
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
    \app\middleware\SessionInit::class,
];
