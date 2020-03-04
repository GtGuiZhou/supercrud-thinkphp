<?php
declare (strict_types = 1);

namespace app\middleware;

use app\exceptions\NoLoginException;
use app\service\AuthService;

class LoginPolicy
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @param $authUserModel
     * @return Response
     * @throws NoLoginException
     */
    public function handle($request, \Closure $next)
    {
        if(!app()->auth->isLogin())
            throw new NoLoginException('请先登录');
        return  $next($request);
    }
}
