<?php
declare (strict_types = 1);

namespace app\middleware;

use app\exceptions\CheckException;
use app\service\SmsCaptchaService;

class CheckSmsCaptcha
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
        $phone = $request->post('phone');
        $captcha = $request->post('captcha');
        if (!SmsCaptchaService::check($phone,$captcha)){
            throw new CheckException('手机验证码错误或已超时');
        }
        SmsCaptchaService::lose($phone,$captcha);
        return  $next($request);
    }
}
