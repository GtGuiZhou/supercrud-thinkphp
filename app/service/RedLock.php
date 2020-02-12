<?php

namespace app\service;

/**
 * 源码:https://github.com/ronnylt/redlock-php/blob/master/src/RedLock.php
 * 理论:http://www.redis.cn/topics/distlock.html
 * redis分布式锁
 * Class RedLock
 * @package app\service
 */
class RedLock
{
    private $retryDelay;
    private $retryCount;
    private $clockDriftFactor = 0.01;

    private $quorum;

    private $servers = array();
    private $instances = array();


    function __construct(array $servers, $retryDelay = 200, $retryCount = 3)
    {
        $this->servers = $servers;

        $this->retryDelay = $retryDelay;
        $this->retryCount = $retryCount;
        // 保证两个客户端不会同时都能抢到锁,也就意味着客户端必须拿到count($servers) / 2 + 1个redis实例数量锁
        $this->quorum  = min(count($servers), (count($servers) / 2 + 1));
    }

    public function lock($resource, $ttl)
    {
        $this->initInstances();

        $token = uniqid();
        $retry = $this->retryCount;

        do {
            $n = 0;

            $startTime = microtime(true) * 1000;

            foreach ($this->instances as $instance) {
                if ($this->lockInstance($instance, $resource, $token, $ttl)) {
                    $n++;
                }
            }

            # Add 2 milliseconds to the drift to account for Redis expires
            # precision, which is 1 millisecond, plus 1 millisecond min drift
            # for small TTLs.
            $drift = ($ttl * $this->clockDriftFactor) + 2; // 漂移时间,简单来说就是不同计算机之间的时间可能存在细微的差别
            // 加锁过程中所用的时间和漂移时间是否超过了第一把锁的有效期
            $validityTime = $ttl - (microtime(true) * 1000 - $startTime) - $drift;


            if ($n >= $this->quorum && $validityTime > 0) {
                return [
                    'validity' => $validityTime,
                    'resource' => $resource,
                    'token'    => $token,
                ];

            } else {
                // 加锁失败释放掉加的锁,准备进入下一轮抢锁
                foreach ($this->instances as $instance) {
                    $this->unlockInstance($instance, $resource, $token);
                }
            }


            // 随机等待一段时间,防止多个客户端同时发起抢锁
            // Wait a random delay before to retry
            $delay = mt_rand(floor($this->retryDelay / 2), $this->retryDelay); // mt_rand比rand快4倍
            usleep($delay * 1000);
            $retry--;

        } while ($retry > 0);

        return false;
    }

    public function unlock(array $lock)
    {
        $this->initInstances();
        $resource = $lock['resource'];
        $token    = $lock['token'];

        foreach ($this->instances as $instance) {
            $this->unlockInstance($instance, $resource, $token);
        }
    }

    private function initInstances()
    {
        if (empty($this->instances)) {
            foreach ($this->servers as $server) {
                list($host, $port, $timeout) = $server;
                $redis = new \Redis();
                $redis->connect($host, $port, $timeout);

                $this->instances[] = $redis;
            }
        }
    }

    private function lockInstance($instance, $resource, $token, $ttl)
    {
        return $instance->set($resource, $token, ['NX', 'PX' => $ttl]);
    }

    // token用来防止当前客户端加锁超时后,别的客户端上锁后,误删别的客户端加的锁
    private function unlockInstance($instance, $resource, $token)
    {
        // 通过lua脚本,请求一次redis实例即可
        $script = '
            if redis.call("GET", KEYS[1]) == ARGV[1] then
                return redis.call("DEL", KEYS[1])
            else
                return 0
            end
        ';
        return $instance->eval($script, [$resource, $token], 1);
    }
}
