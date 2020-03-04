<?php
declare (strict_types = 1);

namespace app\middleware;

use app\service\AuthService;

class RegisterAuth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next,$authUserModelClass)
    {
        //
        app()->bind('auth',new AuthService(new $authUserModelClass));
        return $next($request);
    }
}
