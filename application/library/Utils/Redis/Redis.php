<?php

namespace Library\Utils\Redis;

use Library\Utils\Redis\Exception\CacheInvalidException;

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
 * @method static select(string $string)
 */
class Redis
{
    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws CacheInvalidException
     */
    public function __call($name, $arguments)
    {
        return self::__callStatic($name, $arguments);
    }

    /*
     * @param $name
     * @param $arguments
     * @param null $connectionName
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        try {
            if ($name == 'select') {
                CacheProvider::getInstance()->select(head($arguments));
                return new static();
            } else {
                return CacheProvider::getInstance()
                    ->callCacheDriver($name, $arguments);
            }
        } catch (\Exception $exception) {
            throw new CacheInvalidException($exception->getMessage());
        }
    }
}
