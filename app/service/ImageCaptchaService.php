<?php


namespace app\service;


use Gregwar\Captcha\CaptchaBuilder;
use think\facade\Cache;

class ImageCaptchaService
{

    public static function build()
    {
        $builder = new CaptchaBuilder();
        $content = $builder->build()->getContents();
        $captcha = $builder->getPhrase();
        imagejpeg($content);
        $content = ob_get_clean();
        $captcha = strtolower($captcha);
        Cache::set('imagecaptcha:'.$captcha,96400);
        return response($content, 200, ['Content-Length' => strlen($content)])->contentType('image/jpeg');
    }

    public static function check($captcha)
    {
        $captcha = strtolower($captcha);
        return Cache::has("imagecaptcha:$captcha");
    }

    public static function lose($captcha)
    {
        Cache::delete("imagecaptcha:$captcha");
    }
}