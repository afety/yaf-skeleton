<?php

namespace Library\Utils\Redis;

/**
 * Class Redis
 * @package Library\Utils\Redis
 * @method static get(string $string, $default = null)
 * @method static set($key, $value, $ttl = null)
 * @method static delete(string $key)
 * @method static clear()
 * @method static getMultiple($keys, $default = null)
 * @method static setMultiple($values, $ttl = null)
 * @method static deleteMultiple($keys)
 * @method static has(string $string)
 */
class Redis
{
    /*
     * @param $name
     * @param $arguments
     * @param null $connectionName
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return CacheProvider::getInstance()
            ->callCacheDriver($name, $arguments);
    }
}
