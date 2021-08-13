<?php

namespace Library\Utils\Redis;

use Library\Utils\Redis\Driver\DriverInterface;
use Library\Utils\Redis\Driver\RedisDriver;
use Library\Utils\Redis\Exception\InvalidArgumentException;

class CacheProvider
{
    /**
     * @var self null
     */
    private static $instance = null;

    /**
     * @var array DriverInterface
     */
    private $connections = [];

    /**
     * @var string
     */
    private $default = 'default';

    /**
     * @var string
     */
    private $temporaryConnection = '';

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
     * @return DriverInterface[]
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * @param string $name
     * @return DriverInterface|null
     */
    public function getConnect(string $name = 'default')
    {
        return $this->connections[$name] ?? null;
    }

    /**
     * @param string $connectionName
     * @return bool
     * @throws InvalidArgumentException
     */
    public function select(string $connectionName)
    {
        if (!array_key_exists($connectionName, $this->connections)) {
            throw new InvalidArgumentException("Redis: connection '$connectionName' not existed ");
        }

        $this->temporaryConnection = $connectionName;
        return true;
    }

    /**
     * @return bool
     */
    private function resetConnection()
    {
        $this->temporaryConnection = '';
        $this->default = 'default';
        return true;
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
        $connectionName = $this->default;
        if (!empty($this->temporaryConnection)) $connectionName = $this->temporaryConnection;
        $this->resetConnection();

        return $this->connections[$connectionName]->$method(...$arguments);
    }
}