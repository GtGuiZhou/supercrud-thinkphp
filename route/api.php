<?php

use think\facade\Route;

Route::group('api/captcha', function () {
    Route::get('image', 'image')
        ->middleware(\app\middleware\IntervalGuard::class, 2, 2);
    Route::get('sms/:phone', 'sms')
        ->middleware(\app\middleware\IntervalGuard::class, 60, 1);
    Route::post('email', 'email')
        ->middleware(\app\middleware\IntervalGuard::class, 60, 1);
})->prefix('api.captcha/');


// 上传文件
Route::group('api/file',function (){
    Route::post('upload','api.file/upload');
    Route::post('uploadMulit','api.file/uploadMulit');
    Route::get(':md5','api.file/read');
    Route::get('local/:md5','api.file/readLocal');
})->pattern([
    'md5' => '\w+'
]);;

