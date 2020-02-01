<?php


namespace app\service;


use Overtrue\EasySms\EasySms;
use think\facade\Cache;
use think\helper\Str;

class SmsCaptchaService
{

    public static function send($phone)
    {
        $sms = new EasySms(config('sms'));
        $captcha = Str::random(5, 1);
        $sms->send($phone, [
            "content" => "打扰您了,你的验证码:" . $captcha,
            "template" => "56062",
            "data" => [
                'code' => $captcha
            ]
        ]);
        Cache::set("smscaptcha:$phone:$captcha",1,60);
    }

    public static function check($phone,$captcha)
    {
        return Cache::has("smscaptcha:$phone:$captcha");
    }
}