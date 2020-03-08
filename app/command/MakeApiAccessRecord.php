<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\Table;
use think\Db;
use think\event\RouteLoaded;

/**
 * 构造api访问记录
 * Class MakeApiAccessRecord
 * @package app\command
 */
class MakeApiAccessRecord extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('maar')
            ->setDescription('构造api访问记录');
    }

    protected function execute(Input $input, Output $output)
    {
        $rules = $this->getRouteList();
        for ($k = 0; $k < 1000; $k++) {
            $rows = [];
            for ($i = 0; $i < 10000; $i++) {
                $api = $rules[rand(0, count($rules) - 1)];
                $time = time() - rand(0, 7 * 86400); // 随机获取最近七天的日期
                $create_time = date('Y-m-d H:i:s', $time);
                $week = date('w',$time);
                $hour = date('H',$time);
                $time = (float)(rand(0, 1000) .'.'. rand(0, 100));
                $osList = ['linux', 'mac', 'windows7', 'windows7', 'android', 'ios'];
                $os = $osList[rand(0, count($osList) - 1)];
                $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
                $uaList = ['chrome', 'firefox', 'opera', 'qq', '360', 'ie', 'edge', 'uc'];
                $ua = $uaList[rand(0, count($uaList) - 1)];
                $crawler = rand(0, 1) ? 'yes' : 'no';
                $deviceList = ['apple', 'microsoft', 'htc', 'xiaomi', 'sony', 'samsung', 'vivo', 'oppo'];
                $device = $deviceList[rand(0, count($deviceList) - 1)];
                $rows[] = compact('api', 'os', 'ua', 'device', 'crawler', 'ip', 'hour', 'time', 'create_time', 'week');
            }
            \think\facade\Db::table('api_access_record')->insertAll($rows);
            echo $k . "\n";
        }

    }


    protected function getRouteList(string $dir = null)
    {
        $this->app->route->setTestMode(true);
        $this->app->route->clear();

        if ($dir) {
            $path = $this->app->getRootPath() . 'route' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR;
        } else {
            $path = $this->app->getRootPath() . 'route' . DIRECTORY_SEPARATOR;
        }

        $files = is_dir($path) ? scandir($path) : [];

        foreach ($files as $file) {
            if (strpos($file, '.php')) {
                include $path . $file;
            }
        }

        //触发路由载入完成事件
        $this->app->event->trigger(RouteLoaded::class);

        $table = new Table();

        if ($this->input->hasOption('more')) {
            $header = ['Rule', 'Route', 'Method', 'Name', 'Domain', 'Option', 'Pattern'];
        } else {
            $header = ['Rule', 'Route', 'Method', 'Name'];
        }

        $table->setHeader($header);

        $routeList = $this->app->route->getRuleList();
        $rows = [];

        foreach ($routeList as $item) {
            $rows[] = $item['method'] . '-' . $item['rule'];
        }
        return $rows;
    }


}
