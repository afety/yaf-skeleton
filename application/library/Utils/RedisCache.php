<?php

namespace Library\Utils;

use Yaf\Registry;
use Redis;

class RedisCache
{
    /**
     * @var Redis|null
     */
    private static $_redis = null;

    /**
     * @return Redis|null
     */
    private static function connect()
    {
        if (is_null(self::$_redis)) {
            $conf = Registry::get('config')->get('redis.params');
            self::$_redis = new Redis();
            self::$_redis->connect(REDIS_HOST, REDIS_PORT, 3);
            self::$_redis->auth(REDIS_PASSWORD);
            self::$_redis->select(REDIS_DATABASE);
        }
        return self::$_redis;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (is_null(self::$_redis)) {
            self::connect();
        }

        return self::$_redis->$name(...$arguments);
    }
}