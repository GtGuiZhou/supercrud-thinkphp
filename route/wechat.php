<?php


use think\facade\Route;

Route::group('wechat/official',function (){
    // 微信鉴定服务器
    Route::any('server-validate','wechat.official/serverValidate');
    // 微信授权
    Route::any('auth','wechat.official/auth');
    // 授权成功跳转到该接口
    Route::any('callback','wechat.official/callback')
        ->middleware(\app\middleware\RegisterAuth::class,\app\model\UserModel::class);
});



