<?php


namespace app\service;


use Overtrue\EasySms\EasySms;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use think\facade\Cache;
use think\helper\Str;
use think\Session;

class EmailCaptchaService
{



    public static function send($email)
    {
        $captcha = Str::random(5, 1);

        $template = <<<EOF
<div style="margin-top: 50px;font-size: 50px;font-weight: bold;text-align: center">
    $captcha
</div>
EOF;


        $transport = (new Swift_SmtpTransport(config('email.host'), config('email.port'),config('email.encryption')))
            ->setUsername(config('email.username'))
            ->setPassword(config('email.password'))
        ;

// Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

// Create a message
        $message = (new Swift_Message('邮箱验证码'))
            ->setFrom([config('email.username')])
            ->setTo([$email])
            ->setBody($template,config('email.content_type','text/plain'))
        ;

// Send the message
        $mailer->send($message);

        Cache::set("emailcaptcha:$email:$captcha", 1, 120);
    }

    public static function check($email, $captcha,$lose = false)
    {
        $res =  Cache::has("emailcaptcha:$email:$captcha");
        if ($lose) self::lose($email,$captcha);
        return $res;
    }

    public static function lose($email, $captcha)
    {
        return Cache::delete("emailcaptcha:$email:$captcha");
    }
}