<?php

declare(strict_types = 1);

namespace Library\Utils\Redis\Driver;

use Library\Utils\Redis\Exception\CacheException;
use Library\Utils\Redis\Exception\InvalidArgumentException;
use Predis\Client;

class RedisDriver implements DriverInterface
{
    /**
     * @var Client null
     */
    private $client = null;

    /**
     * RedisDriver constructor.
     * @param array $settings
     * @throws CacheException
     */
    public function __construct(array $settings)
    {
        if (!extension_loaded('redis')) {
            throw new CacheException("PHP Redis Extension is not installed");
        }

        $config = [
            'scheme' => 'tcp',
            'host' => '',
            'port' => 6379,
            'password' => '',
            'database' => 0,
            'timeout' => 3,
            'read_write_timeout' => 3,
        ];

        foreach ($settings as $key => $value) {
            if (!isset($config[$key])) continue;

            $config[$key] = $value;
        }

        $this->client = new Client($config, ['prefix' => $settings['prefix'] ?? '']);
    }

    /**
     * @return Client
     */
    private function connectedClient()
    {
        if (!$this->client->isConnected()) {
            $this->client->connect();
        }

        return $this->client;
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key The unique key of this item in the cache.
     * @param mixed $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     *
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function get($key, $default = null)
    {
        $value = $this->connectedClient()->get($key);

        return is_null($value) ? $default : unserialize($value);
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key The key of the item to store.
     * @param mixed $value The value of the item to store, must be serializable.
     * @param null|int $ttl Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function set($key, $value, $ttl = null)
    {
        if (is_null($ttl)) {
            return !!$this->connectedClient()->set($key, serialize($value));
        } else {
            return !!$this->connectedClient()->setex($key, $ttl, serialize($value));
        }
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function delete($key)
    {
        return $this->connectedClient()->del($key);
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear()
    {
        return $this->connectedClient()->flushall();
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys A list of keys that can obtained in a single operation.
     * @param mixed $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function getMultiple($keys, $default = null)
    {
        if (!is_iterable($keys)) {
            throw new InvalidArgumentException("Key must be iterable");
        }

        $content = [];
        foreach ($keys as $key) {
            $content = $this->connectedClient()->get($key);
        }

        return $content;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable $values A list of key => value pairs for a multiple-set operation.
     * @param null|int $ttl Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     *   MUST be thrown if $values is neither an array nor a Traversable,
     *   or if any of the $values are not a legal value.
     */
    public function setMultiple($values, $ttl = null)
    {
        // TODO: Implement setMultiple() method.
        foreach ($values as $key => $value) {
            $this->set($key, $values, $ttl);
        }

        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function deleteMultiple($keys)
    {
        if (!is_iterable($keys)) {
            throw new InvalidArgumentException("Key must be iterable");
        }

        foreach ($keys as $key) {
            $this->connectedClient()->del($key);
        }

        return true;
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it making the state of your app out of date.
     *
     * @param string $key The cache item key.
     *
     * @return bool
     *
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function has($key)
    {
        return !!$this->connectedClient()->exists($key);
    }
}