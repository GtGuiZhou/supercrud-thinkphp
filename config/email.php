<?php

return [
    'host' => env('email.host',''),
    // 端口,建议使用465,25端口容易被封堵
    'port' => env('email.port','25'),
    'username' => env('email.username',''),
    'password' => env('email.password',''),
    // 通讯加密方式,端口选465必填
    'encryption' => env('email.encryption',''),
    'content_type' => env('email.content_type','text/html')
];