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

function fastCrud($prefix){
    Route::get($prefix,$prefix.'/index');
    Route::post($prefix,$prefix.'/insert');
    Route::delete($prefix.'/:id',$prefix.'/delete');
    Route::put($prefix.'/:id',$prefix.'/update');
}

function fastPost($method){
    return Route::post($method,$method);
}

function fastGet($method){
    return Route::get($method,$method);
}

function fastDelete($method){
    return Route::get($method,$method);
}

function fastPut($method){
    return Route::get($method,$method);
}


//Route::get('index','index.index/index');
Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');


Route::group('user',function (){
   Route::post('login','login');
   Route::post('register','register');
   Route::put('updatePassword','updatePassword');
})->prefix('user/');


Route::group('admin',function (){
        Route::post('login','auth/login')
            ->middleware(\app\middleware\CheckImageCapcha::class);
        Route::post('register','auth/register')
            ->middleware(\app\middleware\CheckImageCapcha::class);
        Route::put('updatePassword','auth/updatePassword');
        Route::get('rulesTree','auth/rulesTree');
        Route::get('rulesList','auth/rulesList');

        fastCrud('rule');

        fastCrud('childrenRole');

        fastCrud('childrenAdmin');
        Route::get('childrenAdmin/:id/rulesTree','childrenAdmin/rulesTree');
        Route::get('childrenAdmin/:id/rulesList','childrenAdmin/rulesList');
})->prefix('admin.')
    ->middleware(\app\middleware\AuthMiddleware::class,'admin');


Route::group('api/captcha',function (){
    Route::get('image','image');
    Route::get('sms/:phone','sms');
    Route::post('email','email');
})->prefix('api.captcha/');
