<?php


namespace app\controller;


class Test
{
    public function index()
    {


        return json(app()->route->getRuleList());
//        return json(request()->rule()->getRule());
//        return json();
    }
}