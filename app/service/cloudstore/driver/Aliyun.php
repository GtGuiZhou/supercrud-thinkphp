<?php


namespace app\service\cloudstore\driver;


use app\service\cloudstore\Driver;
use OSS\OssClient;

class Aliyun extends Driver
{

    protected $client;
    protected $bucket;

    public function __construct($config)
    {
        $this->bucket = $config['bucket'];
        if (strpos($config['region'],'http') !== 0)
            $config['region'] = $config['schema'].'://'.$config['region'];
        $this->client = new OssClient($config['secret_id'], $config['secret_key'], $config['region']);
    }

    public function store($body, $path = null): string
    {
        $res =  $this->client->putObject($this->bucket, $path, $body);
        return  $res['oss-request-url'];
    }
}