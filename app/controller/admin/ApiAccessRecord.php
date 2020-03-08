<?php


namespace app\controller\admin;


use app\AdminController;
use think\db\BaseQuery;
use think\facade\Db;

class ApiAccessRecord extends AdminController
{
    /**
     * @var BaseQuery
     */
    protected $query;

    protected function initialize()
    {
        $this->query = Db::table('api_access_record');
    }

    public function crawler()
    {
        return json(
            Db::query("SELECT crawler,count(*) as `count` FROM `api_access_record` GROUP BY `crawler`")
        );
    }

    public function hour()
    {
        $today = Db::query("SELECT hour,count(*) as `count` FROM `api_access_record` WHERE to_days(create_time) = to_days(now())  GROUP BY `hour` ORDER BY `hour` ASC");
        $all = Db::query("SELECT hour,count(*) as `count` FROM `api_access_record` GROUP BY `hour` ORDER BY `hour` ASC");
        $minDate = Db::query("SELECT min(create_time) as `date` FROM api_access_record LIMIT 1");
        $maxDate = Db::query("SELECT max(create_time) as `date` FROM api_access_record LIMIT 1");
        if (count($minDate) < 1 || count($maxDate) < 1) {
            $diff_days = 1;
        } else {
            $diff_days = date_diff(date_create($minDate[0]['date']), date_create($maxDate[0]['date']))->days ?? 1;
        }
        return json(compact('today', 'all','diff_days'));
    }


    public function week()
    {
        return json(Db::query("SELECT week,count(*) as `count` FROM `api_access_record` GROUP BY `week` ORDER BY `week` ASC"));
    }

    public function api()
    {
        return json( Db::query("SELECT api,count(*) as `count`,avg(time) as `avg` FROM `api_access_record` GROUP BY `api` ORDER BY avg DESC LIMIT 20"));
    }

    public function ip()
    {
        return json(Db::query("SELECT ip,count(*) as `count` FROM `api_access_record` GROUP BY `api` ORDER BY count DESC LIMIT 20"));
    }

    public function os()
    {
        return json(Db::query("SELECT os,count(*) as `count` FROM `api_access_record` GROUP BY `os`  ORDER BY `count` DESC LIMIT 10"));
    }

    public function device()
    {
        return json(Db::query("SELECT device,count(*) as `count` FROM `api_access_record` GROUP BY `device` ORDER BY `count` DESC LIMIT 10"));
    }

    public function index()
    {
//        return $this->query->group('api')->field('api,count(*) as count')->order('count','desc')->fetchSql(true)->select();
        return json([
            'ua' => Db::query("SELECT ua,count(*) as `count` FROM `api_access_record` GROUP BY `ua`"),
            'os' => Db::query("SELECT os,count(*) as `count` FROM `api_access_record` GROUP BY `os`"),
            'device' => Db::query("SELECT device,count(*) as `count` FROM `api_access_record` GROUP BY `device`"),
            'api' => Db::query("SELECT api,count(*) as `count` FROM `api_access_record` GROUP BY `api`"),
            'ip' => Db::query("SELECT ip,count(*) as `count` FROM `api_access_record` GROUP BY `ip` ORDER BY `count` DESC LIMIT 100"),
            'week' => Db::query("SELECT week,count(*) as `count` FROM `api_access_record` GROUP BY `week` ORDER BY `week` ASC"),
            'hour' => Db::query("SELECT hour,count(*) as `count` FROM `api_access_record` GROUP BY `hour` ORDER BY `hour` ASC"),
            'crawler' => Db::query("SELECT crawler,count(*) as `count` FROM `api_access_record` GROUP BY `crawler`")
        ]);
    }

    private function uaGroup()
    {
        return $this->query->group('ua')->field('ua,count(*) as count')->select();
    }

    private function osGroup()
    {
        return $this->query->group('os')->field('os,count(*) as count')->select();
    }

    private function deviceGroup()
    {
        return $this->query->group('device')->field('device,count(*) as count')->select();
    }

    private function apiGroup()
    {
        return $this->query->group('api')->field('api,count(*) as count')->order('count', 'desc')->select();
    }

    private function ipGroup()
    {
        return $this->query->group('api')->field('api,count(*) as count')->order('count', 'desc')->limit(100)->select();
    }

    private function weekGroup()
    {
        return $this->query->group('week')->field('week,count(*) as count')->select();
    }

    private function hourGroup()
    {
        return $this->query->group('hour')->field('hour,count(*) as count')->select();
    }

    private function crawlerGroup()
    {
        return $this->query->group('crawler')->field('crawler,count(*) as count')->select();
    }
}