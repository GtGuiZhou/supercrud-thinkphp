<?php
declare (strict_types = 1);

namespace app\middleware;

use app\exceptions\MiddlewareException;
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
     * @throws MiddlewareException
     */
    public function handle($request, \Closure $next)
    {
        $captcha = $request->post('captcha');
        $email = $request->post('email');
        if (!$captcha){
            throw new MiddlewareException('请输入邮箱验证码');
        }
        if (!EmailCaptchaService::check($email,$captcha)){
            throw new MiddlewareException('邮箱验证码错误');
        }
        return  $next($request);
    }
}
