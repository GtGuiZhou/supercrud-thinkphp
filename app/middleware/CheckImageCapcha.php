<?php
declare (strict_types = 1);

namespace app\middleware;

use app\exceptions\MiddlewareException;
use app\service\ImageCaptchaService;

class CheckImageCapcha
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     * @throws MiddlewareException
     */
    public function handle($request, \Closure $next,$fieldName = 'captcha')
    {
        $captcha = $request->post($fieldName);
        if (!$captcha){
            throw new MiddlewareException('请输入验证码');
        }
        if (!ImageCaptchaService::check($captcha)){
            throw new MiddlewareException('验证码错误');
        }
        return  $next($request);
    }
}
