<?php

return [
    'pay' => [
        // 必要配置
        'app_id'             => 'xxxx',
        'mch_id'             => 'your-mch-id',
        'key'                => 'key-for-signature',   // API 密钥

        // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
        'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！

        'notify_url'         => '默认的订单回调地址',     // 你也可以在下单时单独设置来想覆盖它
    ],
    'official' => [
        'app_id' => '',
        'secret' => '',
        // 公众号验证服务器的时候使用
        'token' => 'testtoken',
        // snsapi_base仅能获取openid,snsapi_userinfo获取用户的基本信息，snsapi_login开放平台使用
        'scope' => 'snsapi_userinfo',
        // 授权回调登录后会跳转到该地址
        'target_url' => '',
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',
    ]
];
