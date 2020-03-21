<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::pattern([
    'id'   => '\d+',
]);

Route::get('test','test/index');

Route::group('', function () {
    Route::post('login', 'user.auth/login')
        ->middleware(\app\middleware\IntervalGuard::class, 60, 10)
        ->middleware(\app\middleware\CheckImageCapcha::class);
    Route::post('register', 'user.auth/register')
        ->middleware(\app\middleware\CheckImageCapcha::class);
    // 活动列表
    Route::get('/self', 'user.auth/self');
})->middleware(\app\middleware\AuthMiddleware::class, 'user');








