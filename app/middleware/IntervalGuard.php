<?php
declare (strict_types = 1);

namespace app\middleware;

use app\exceptions\MiddlewareException;
use think\facade\Cache;

class IntervalGuard
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @param int $intervalSecond  间隔时间
     * @param int $maxCount 最多访问几次
     * @param int|null $penaltyTime 超过罚时多少秒
     * @return void
     * @throws MiddlewareException
     */
    public function handle($request, \Closure $next,$intervalSecond,$maxCount,$penaltyTime = null)
    {
        if ($penaltyTime == null)
            $penaltyTime = $intervalSecond;

        $ip = $request->ip();
        $url = $request->url();
        $expireKey = "interval_guard_expire:$ip:$url";
        if (Cache::has($expireKey)){
            $key = "interval_guard_count:$ip:$url";
            Cache::inc($key);
            $count = Cache::get($key,0);
            if ($count > $maxCount){
                Cache::set($expireKey,1,$penaltyTime);
                throw new MiddlewareException("操作频繁,请等待{$penaltyTime}s后在试");
            }
        }
        Cache::set($expireKey,1,$intervalSecond);
        return $next($request);
    }
}
