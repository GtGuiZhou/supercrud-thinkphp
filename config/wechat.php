<?php

return [
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
