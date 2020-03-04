<?php

use think\facade\Route;

if (!function_exists('fastCrud')) {
    function fastCrud($prefix, $name)
    {
        Route::get($prefix, $prefix . '/index')->name("查看")->option([
            '__GROUP__' => $name
        ]);
        Route::post($prefix, $prefix . '/insert')->name("新增")->option([
            '__GROUP__' => $name
        ]);
        Route::delete($prefix . '/:id', $prefix . '/delete')->name("删除")->option([
            '__GROUP__' => $name
        ]);
        Route::put($prefix . '/:id', $prefix . '/update')->name("修改")->option([
            '__GROUP__' => $name
        ]);
    }
}


if (!function_exists('fastPost')) {
    function fastPost($method)
    {
        return Route::post($method, $method);
    }
}

if (!function_exists('fastGet')) {
    function fastGet($method)
    {
        return Route::get($method, $method);
    }
}
if (!function_exists('fastDelete')) {
    function fastDelete($method)
    {
        return Route::delete($method, $method);
    }
}
if (!function_exists('fastPut')) {
    function fastPut($method)
    {
        return Route::put($method, $method);
    }
}


/**
 * 不需要登录，不需要鉴权
 */
Route::group('admin', function () {
    Route::post('login', 'auth/login')
        ->middleware(\app\middleware\IntervalGuard::class, 60, 10)
        ->middleware(\app\middleware\CheckImageCapcha::class);
    Route::post('register', 'auth/register')
        ->middleware(\app\middleware\CheckImageCapcha::class);
})->middleware(\app\middleware\RegisterAuth::class, \app\model\AdminModel::class)
    ->prefix('admin.');


/**
 * 需要登录，不需要鉴权
 */
Route::group('admin', function () {
    Route::put('logout', 'auth/logout');
    Route::put('updatePassword', 'auth/updatePassword');
    Route::get('menu', 'auth/menu');

    //    规则
    Route::get('rule', 'rule/index')->name('查看')->option(['__GROUP__' => '规则']);
})->prefix('admin.')
    ->middleware(\app\middleware\RegisterAuth::class, \app\model\AdminModel::class)
    ->middleware(\app\middleware\LoginPolicy::class);


/**
 * 需要登录，需要鉴权
 */
Route::group('admin', function () {
//    角色
    fastCrud('role', '角色');
    Route::put('role/:id/menu', 'role/updateMenu')->name('修改角色菜单')->option(['__GROUP__' => '角色']);
    Route::put('role/:id/rule', 'role/updateRule')->name('修改角色规则')->option(['__GROUP__' => '角色']);
//    管理员
    fastCrud('admin', '管理员');
    Route::put('admin/:id/root', 'admin/updateRoot')->name('切换超级管理员')->option(['__GROUP__' => '管理员']);
})->prefix('admin.')
    ->middleware(\app\middleware\RegisterAuth::class, \app\model\AdminModel::class)
    ->middleware(\app\middleware\LoginPolicy::class)
    ->middleware(\app\middleware\RulePolicy::class);