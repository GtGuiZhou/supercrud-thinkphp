<?php

return [
    /**
     * '权限名称' => [
     *      'driver' => 权限会话维持驱动
     * ]
     */
  'admin' => [
      'driver' => \app\middleware\auth\TokenAuthDriver::class
  ],
    'user' => [
        'driver' => \app\middleware\auth\TokenAuthDriver::class
    ]
];
