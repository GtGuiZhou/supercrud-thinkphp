<?php
declare (strict_types = 1);

namespace app\middleware;

use app\exceptions\NoLoginException;
use app\middleware\auth\Auth;

class AuthMiddleware
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next,$authName)
    {
        bind('auth',new Auth($authName));
        return  $next($request);
    }
}
