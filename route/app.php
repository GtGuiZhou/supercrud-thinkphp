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

function fastCrud($prefix)
{
    Route::get($prefix, $prefix . '/index');
    Route::post($prefix, $prefix . '/insert');
    Route::delete($prefix . '/:id', $prefix . '/delete');
    Route::put($prefix . '/:id', $prefix . '/update');
}

function fastPost($method)
{
    return Route::post($method, $method);
}

function fastGet($method)
{
    return Route::get($method, $method);
}

function fastDelete($method)
{
    return Route::get($method, $method);
}

function fastPut($method)
{
    return Route::get($method, $method);
}

Route::group('', function () {
    Route::post('login', 'user.auth/login')
        ->middleware(\app\middleware\IntervalGuard::class, 60, 10)
        ->middleware(\app\middleware\CheckImageCapcha::class);
    Route::post('register', 'user.auth/register')
        ->middleware(\app\middleware\CheckImageCapcha::class);
    // 活动列表
    Route::get('/self', 'user.auth/self');
})->middleware(\app\middleware\AuthMiddleware::class, 'user');


Route::group('admin', function () {
    Route::post('login', 'auth/login')
        ->middleware(\app\middleware\IntervalGuard::class, 60, 10)
        ->middleware(\app\middleware\CheckImageCapcha::class);
    Route::post('register', 'auth/register')
        ->middleware(\app\middleware\CheckImageCapcha::class);
    Route::put('logout', 'auth/logout');
    Route::put('updatePassword', 'auth/updatePassword');
    Route::get('rulesTree', 'auth/rulesTree');
    Route::get('rulesList', 'auth/rulesList');
    Route::get('menu', 'auth/menu');

    fastCrud('rule');

    Route::get('childrenRole/:roleId/rulesList', 'childrenRole/rulesList');
    Route::get('childrenRole/:roleId/rulesTree', 'childrenRole/rulesTree');
    fastCrud('childrenRole');


    fastCrud('childrenAdmin');
    Route::get('childrenAdmin/:id/rulesTree', 'childrenAdmin/rulesTree');
    Route::get('childrenAdmin/:id/rulesList', 'childrenAdmin/rulesList');
})->prefix('admin.')
    ->middleware(\app\middleware\AuthMiddleware::class, 'admin');


Route::group('api/captcha', function () {
    Route::get('image', 'image')
        ->middleware(\app\middleware\IntervalGuard::class, 2, 2);
    Route::get('sms/:phone', 'sms')
        ->middleware(\app\middleware\IntervalGuard::class, 60, 1);
    Route::post('email', 'email')
        ->middleware(\app\middleware\IntervalGuard::class, 60, 1);
})->prefix('api.captcha/');


// 上传文件
Route::post('api/file/upload','api.file/upload');
Route::post('api/file/uploadMulit','api.file/uploadMulit');


Route::any('pay/notify/paid','pay.notify/paid');