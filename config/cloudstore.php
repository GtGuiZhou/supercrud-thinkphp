<?php

return [
    'default' => 'qcloud',
    'driver' => [
        // https://cloud.tencent.com/document/product/436/12266,配置参考
        'qcloud' => [
            'driver' => \app\service\cloudstore\driver\QCloud::class,
            // 存储区域
            'region' => 'ap-chengdu',
            //协议头部，默认为http
            'schema' => 'https',
            'secret_id' => 'AKIDlRev3C9tZ3MTN7c6QdfVAdy86sSdtO2T',
            'secret_key' => 'NYaR6ZZQ84sX0RltthJl6II7sWHvRo37',
            // 存储桶
            'bucket' => 'gtguizhou-1253299711',
        ],
        'aliyun' => [
            'driver' => \app\service\cloudstore\driver\Aliyun::class,
            // 存储区域
            'region' => 'oss-cn-shenzhen.aliyuncs.com',
            //协议头部，默认为http
            'schema' => 'https',
            'secret_id' => 'xxxxxxx',
            'secret_key' => 'xxxxxxxxxx',
            // 存储桶
            'bucket' => 'xxxx',
        ]
    ]
];