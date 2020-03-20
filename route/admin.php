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
    Route::get('', 'auth/index'); // 获取登录信息
    Route::put('logout', 'auth/logout');
    Route::put('password-update', 'auth/passwordUpdate]');
    Route::get('password-check', 'auth/passwordCheck');
    Route::get('login-record', 'auth/loginRecord');
    // 菜单
    Route::get('menu', 'auth/menu');
    // 角色
    Route::get('role/all', 'role/all');
    // 规则
    Route::get('rule', 'rule/index')->name('查看')->option(['__GROUP__' => '规则']);
})->prefix('admin.')
    ->middleware(\app\middleware\RegisterAuth::class, \app\model\AdminModel::class)
    ->middleware(\app\middleware\LoginPolicy::class);


/**
 * 需要登录，需要鉴权
 */
Route::group('admin', function () {
// api访问记录
    Route::get('api-access-record/crawler', 'ApiAccessRecord/crawler')->name('爬虫图表')->option(['__GROUP__' => 'api访问记录']);
    Route::get('api-access-record/api', 'ApiAccessRecord/api')->name('api访问详情图表')->option(['__GROUP__' => 'api访问记录']);
    Route::get('api-access-record/ua', 'ApiAccessRecord/ua')->name('ua分布图表')->option(['__GROUP__' => 'api访问记录']);
    Route::get('api-access-record/os', 'ApiAccessRecord/os')->name('系统分布图表')->option(['__GROUP__' => 'api访问记录']);
    Route::get('api-access-record/ip', 'ApiAccessRecord/ip')->name('ip分布图表')->option(['__GROUP__' => 'api访问记录']);
    Route::get('api-access-record/device', 'ApiAccessRecord/device')->name('设备分布图表')->option(['__GROUP__' => 'api访问记录']);
    Route::get('api-access-record/week', 'ApiAccessRecord/week')->name('星期分布图表')->option(['__GROUP__' => 'api访问记录']);
    Route::get('api-access-record/hour', 'ApiAccessRecord/hour')->name('24小时分布图表')->option(['__GROUP__' => 'api访问记录']);

//    角色
    fastCrud('role', '角色');
    Route::put('role/:id/menu', 'role/updateMenu')->name('修改角色菜单')->option(['__GROUP__' => '角色']);
    Route::put('role/:id/rule', 'role/updateRule')->name('修改角色规则')->option(['__GROUP__' => '角色']);
//    管理员
    fastCrud('admin', '管理员');
    Route::get('admin/:id/login', 'admin/login') // 登录某个管理员的账号
    ->model(\app\model\AdminModel::class)
        ->name('登录管理员')->option(['__GROUP__' => '管理员']);
    Route::put('admin/:id/root', 'admin/updateRoot')
        ->model(\app\model\AdminModel::class)
        ->name('切换超级管理员')->option(['__GROUP__' => '管理员']);
    Route::put('admin/:id/password', 'admin/updatePassword')
        ->model(\app\model\AdminModel::class)
        ->name('修改密码')->option(['__GROUP__' => '管理员']);


// 用户
    fastCrud('user', '用户');
    Route::put('user/:id/password', 'user/updatePassword')
        ->model(\app\model\UserModel::class)
        ->name('修改密码')->option(['__GROUP__' => '用户']);
    Route::put('user/:id/lock', 'user/updateLock')
        ->model(\app\model\UserModel::class)
        ->name('封号')->option(['__GROUP__' => '用户']);

// 配置
    Route::get('config','config/index')->name('查看')->option(['__GROUP__' => '系统配置']);
    Route::post('config','config/save')->name('修改')->option(['__GROUP__' => '系统配置']);
})->prefix('admin.')
    ->middleware(\app\middleware\RegisterAuth::class, \app\model\AdminModel::class)
    ->middleware(\app\middleware\LoginPolicy::class)
    ->middleware(\app\middleware\RulePolicy::class);