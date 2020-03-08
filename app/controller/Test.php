<?php


namespace app\controller;


use Jaybizzle\CrawlerDetect\CrawlerDetect;

class Test
{
    public function index()
    {
        $cd = new CrawlerDetect();
        $res = $cd->getMatches();
        var_dump($res);
//        return json(app()->route->getRuleList());
//        return json(request()->rule()->getRule());
//        return json();
    }
}