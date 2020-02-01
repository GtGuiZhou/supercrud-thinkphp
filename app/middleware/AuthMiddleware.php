<?php
declare (strict_types = 1);

namespace app\middleware;

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
        $authConfig = config('auth');
        $driver = $authConfig[$authName]['driver'];
        bind('auth',new Auth($driver,$authName));
        return  $next($request);
    }
}
