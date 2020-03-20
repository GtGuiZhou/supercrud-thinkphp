<?php
declare (strict_types = 1);

namespace app\middleware;

use app\exceptions\CheckException;
use app\service\EmailCaptchaService;
use app\service\ImageCaptchaService;

class CheckEmailCaptcha
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     * @throws CheckException
     */
    public function handle($request, \Closure $next)
    {
        $captcha = $request->post('captcha');
        $email = $request->post('email');
        if (!$captcha){
            throw new CheckException('请输入邮箱验证码');
        }
        if (!EmailCaptchaService::check($email,$captcha)){
            throw new CheckException('邮箱验证码错误');
        }
        EmailCaptchaService::lose($email,$captcha);
        return  $next($request);
    }
}
