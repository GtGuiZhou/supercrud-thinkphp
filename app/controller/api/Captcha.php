<?php
declare (strict_types=1);

namespace app\controller\api;

use app\exceptions\ControllerException;
use app\service\EmailCaptchaService;
use app\service\ImageCaptchaService;
use app\service\SmsCaptchaService;
use Gregwar\Captcha\CaptchaBuilder;
use think\captcha\facade\Captcha as CaptchaImage;

class Captcha
{
    public function image()
    {
        return ImageCaptchaService::build();
    }

    public function sms($phone)
    {
        if (preg_grep('/^1[3-9]\d{9}$/', [$phone]) === false) {
            throw new ControllerException('手机号码格式错误');
        }

        SmsCaptchaService::send($phone);
    }

    public function email()
    {
        $email = app()->request->post('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ControllerException('邮箱格式错误');
        }

        EmailCaptchaService::send($email);
    }
}
