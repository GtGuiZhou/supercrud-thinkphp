<?php
declare (strict_types=1);

namespace app\middleware;

use think\facade\Cache;
use think\facade\Config;
use think\facade\Db;

class ConfigInit
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $config = Cache::get('config');
        if (is_array($config)) {
            // 配置修改后会保存在缓存中，没有修改的话就用默认的文件配置
            foreach ($config as $key => $item){
                is_array($item) && Config::set($item, $key);
            }
        }
        return $next($request);
    }
}
