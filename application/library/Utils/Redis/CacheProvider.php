<?php

namespace Library\Utils\Redis;

use Library\Utils\Redis\Driver\DriverInterface;
use Library\Utils\Redis\Exception\InvalidArgumentException;

class CacheProvider
{
    /**
     * @var self null
     */
    private static $instance = null;

    /**
     * @var DriverInterface array
     */
    private $connections = [];

    /**
     * @var string
     */
    private $default = 'default';

    /**
     * @return CacheProvider
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * Make this capsule instance available globally.
     */
    public function setAsGlobal()
    {
        self::$instance = $this;
    }

    /**
     * @param string $name
     */
    public function setDefaultConnectionName(string $name)
    {
        $this->default = $name;
    }

    /**
     * @param array $settings
     * @param string $name
     * @return bool
     * @throws InvalidArgumentException
     */
    public function addConnection(array $settings, string $name = 'default')
    {
        if (!isset($settings['driver'])) {
            throw new InvalidArgumentException("Config missing driver");
        }

        $driverName = $settings['driver'];

        $driverClass = self::getDriverClass($driverName);
        $driver = new $driverClass($settings);
        $this->connections[$name] = $driver;

        return true;
    }

    /**
     * @return array
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * @param string $name
     * @return DriverInterface|null
     */
    public function getConnect(string $name)
    {
        return $this->connections[$name] ?? null;
    }

    /**
     * @param string $driverName
     * @return string
     */
    private static function getDriverClass(string $driverName)
    {
        return "Library\\Utils\\Redis\\Driver\\" . ucwords($driverName) . "Driver";
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function callCacheDriver($method, $arguments)
    {
        return $this->connections[$this->default]->$method(...$arguments);
    }
}