<?php
/**
 * 配置查看
 * https://github.com/overtrue/easy-sms
 */

return [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                'qcloud'
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => '/tmp/easy-sms.log',
            ],
            // 阿里云
            'aliyun' => [
                'access_key_id' => '',
                'access_key_secret' => '',
                'sign_name' => '',
            ],
            // 腾讯云
            'qcloud' => [
                'sdk_app_id' => '', // SDK APP ID
                'app_key' => '', // APP KEY
                'sign_name' => '', // 短信签名，如果使用默认签名，该字段可缺省（对应官方文档中的sign）
            ],
            // 阿里云reset
            'aliyunrest' => [
                'app_key' => '',
                'app_secret_key' => '',
                'sign_name' => '',
            ],
            // 云片
            'yunpian' => [
                'api_key' => '824f0ff2f71cab52936axxxxxxxxxx',
            ],
            // 更多支持，查看：https://github.com/overtrue/easy-sms
        ],
];
