<?php
declare (strict_types = 1);

namespace app\middleware;

use app\exceptions\CheckException;

class RulePolicy
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
        $user = app()->auth->user;
        $rule =  $request->rule()->getRule();
        $method = $request->rule()->getMethod();
        // 通过调用当前用户模型中的haveRule方法判断当前用户是否拥有该权限
        if(!$user->haveRule("$method-$rule")){
            throw new CheckException();
        }
        return  $next($request);
    }
}
