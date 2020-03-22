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
            'secret_id' => 'xxxxxxxxxxxx',
            'secret_key' => 'xxxxxxxxxxx',
            // 存储桶
            'bucket' => 'xxxxxx-1253299711',
        ],
        'aliyun' => [
            'driver' => \app\service\cloudstore\driver\Aliyun::class,
            // 存储区域
            'region' => 'oss-cn-shenzhen.aliyuncs.com',
            //协议头部，默认为http
            'schema' => 'https',
            'secret_id' => 'xxxxxxxxxxxxxxxx',
            'secret_key' => 'xxxxxxxxxxxxx',
            // 存储桶
            'bucket' => 'xxxx',
        ]
    ]
];