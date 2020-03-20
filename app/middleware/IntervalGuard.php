<?php
declare (strict_types=1);

namespace app\middleware;

use app\exceptions\CheckException;
use think\facade\Cache;

class IntervalGuard
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @param int $intervalSecond 间隔时间
     * @param int $maxCount 最多访问几次
     * @param int|null $penaltyTime 超过罚时多少秒
     * @return void
     * @throws CheckException
     */
    public function handle($request, \Closure $next, $intervalSecond, $maxCount, $penaltyTime = null)
    {
        if ($penaltyTime == null)
            $penaltyTime = $intervalSecond;

        $ip = $request->ip();
        $url = $request->url();
        $key = "interval_guard_count:$ip:$url";
        Cache::inc($key);
        $count = Cache::get($key, 0);
        if ($count > $maxCount) {
            Cache::set($key,$count,$penaltyTime);
            throw new CheckException("操作频繁,请等待{$penaltyTime}s后在试");
        }
        Cache::set($key,$count,$intervalSecond);
        return $next($request);
    }
}
