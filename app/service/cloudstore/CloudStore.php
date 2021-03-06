<?php


namespace app\service\cloudstore;


use function GuzzleHttp\Psr7\stream_for;

class CloudStore
{

    /**
     * 当前正在使用的设备
     * @var Driver
     */
    protected $driver;
    protected  $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->driver =  new $this->config['driver']($config);
    }

    public function storeByPath($path,$storePath = null) : string
    {
        $handle = fopen($path,'r');
        $file = fread($handle,filesize($path));
        fclose($handle);
        if ($storePath === null) {
            $storePath = md5_file($path).'.'.pathinfo($path,PATHINFO_EXTENSION);
        }
        return $this->driver->store($file,$storePath);
    }

    public function storeByContent($content,$storePath = null) : string
    {
        $storePath = $storePath === null?md5($content):$storePath;
        return $this->driver->store($content,$storePath);
    }
}