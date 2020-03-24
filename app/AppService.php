<?php
declare (strict_types = 1);

namespace app;

use app\service\RedLock;
use EasyWeChat\Factory;
use think\Service;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public function register()
    {
        // redis分布式锁
        $this->app->bind('redlock',new RedLock(config('redlock')));

    }

    public function boot()
    {
        // 服务启动

    }
}
