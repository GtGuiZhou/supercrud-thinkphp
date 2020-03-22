<?php


namespace app\service\cloudstore;


abstract class Driver
{
    protected $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    abstract public  function store($body,$path = null) : string ;

}