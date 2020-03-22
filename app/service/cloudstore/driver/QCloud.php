<?php


namespace app\service\cloudstore\driver;


use app\service\cloudstore\Driver;
use Qcloud\Cos\Client;

class QCloud extends Driver
{
    protected $client;
    protected $bucket;

    public function __construct($config)
    {

        $this->bucket = $config['bucket'];
        $this->client = new Client([
            'region' => $config['region'],
            'schema' => $config['schema'] ?? 'http',
            'credentials' => [
                'secretId' => $config['secret_id'],
                'secretKey' => $config['secret_key'],
            ],
        ]);
        parent::__construct($config);
    }

    public function store($body, $path = null): string
    {
        $res = $this->client->upload($this->bucket, $path, $body);
        return $this->config['schema'] . '://' . $res['Location'];
    }

}