<?php

namespace EasySwoole\RedisPool;

use EasySwoole\Component\Singleton;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Pool\Config as PoolConfig;
use EasySwoole\Redis\Redis as RedisClient;
use EasySwoole\Redis\RedisCluster;
use EasySwoole\RedisPool\Exception\Exception;

class Redis
{
    use Singleton;
    protected $container = [];

    function register(string $name, RedisConfig $config,?string $cask = null): PoolConfig
    {
        if(isset($this->container[$name])){
            //已经注册，则抛出异常
            throw new RedisPoolException("redis pool:{$name} is already been register");
        }
        if($cask){
            $ref = new \ReflectionClass($cask);
            if((!$ref->isSubclassOf(RedisClient::class)) && (!$ref->isSubclassOf(RedisCluster::class))){
                throw new Exception("cask {$cask} not a sub class of EasySwoole\Redis\Redis or EasySwoole\Redis\RedisCluster");
            }
        }
        $pool = new RedisPool($config,$cask);
        $this->container[$name] = $pool;
        return $pool->getConfig();
    }

    function get(string $name): ?RedisPool
    {
        if (isset($this->container[$name])) {
            return $this->container[$name];
        }
        return null;
    }

    function pool(string $name): ?RedisPool
    {
        return $this->get($name);
    }

    static function defer(string $name,$timeout = null):?RedisClient
    {
        $pool = static::getInstance()->pool($name);
        if($pool){
            return $pool->defer($timeout);
        }else{
            return null;
        }
    }

    static function invoke(string $name,callable $call,float $timeout = null)
    {
        $pool = static::getInstance()->pool($name);
        if($pool){
            return $pool->invoke($call,$timeout);
        }else{
            return null;
        }
    }
}