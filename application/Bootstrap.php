<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher as EventDispatcher;
use Jenssegers\Mongodb\Connection;
use Library\Exceptions\MyErrorException;
use Library\Utils\Redis\CacheProvider;
use Yaf\Application;
use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;
use Yaf\Registry;

class Bootstrap extends Bootstrap_Abstract
{
    protected $config;

    /**
     *
     */
    public function _initErrorHandler()
    {
        /**
         * severity 错误严重程度，定义可查 https://www.php.net/manual/en/errorfunc.constants.php
         */
        set_error_handler(function ($severity, $errStr, $errFile, $errLine, $errContext) {
            if (error_reporting() === 0) { // error was suppressed with the @-operator
                return false;
            }

            // 应用错误 发送通知给维护人员
            throw new MyErrorException($errStr, 599, $severity, $errFile, $errLine);
        });
    }

    /**
     * 把配置存到注册表
     */
    public function _initConfig() {
        $this->config = Application::app()->getConfig();
        Registry::set('config', $this->config);
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function _initPlugin(Dispatcher $dispatcher)
    {
        $user = new UserPlugin();
        $dispatcher->registerPlugin($user);
    }

    /**
     * 加载框架用常量定义目录
     */
    public function _initAppConstants()
    {
        include_once joinPaths(APPLICATION_PATH, "config/constant.php");
    }

    /**
     * 初始化Eloquent 数据库链接
     */
    public function _initEloquent()
    {
        $capsule = new Capsule();

        // 从配置中获取数据库连接设置
        $databaseConfig = include joinPaths(APPLICATION_PATH, '/config/database.php');

        $connections = $databaseConfig['connections'];

        foreach ($connections as $name => $connection) {
            if ($name == 'mysql') {
                // add MySQL connections
                $this->addMysqlConnections($capsule, $connection);
            } else if ($name == 'mongodb') {
                // add MongoDB connections
                $this->addMongodbConnections($capsule, $connection);
            }
        }

        // 设置mongodb数据库支持，如果name是mongodb，交给Jenssegers\Mongodb\Connection来处理
        $capsule->getDatabaseManager()->extend('mongodb', function ($config) {
            return new Connection($config);
        });

        // capsule设置为全局对象
        $capsule->setAsGlobal();

        // 注册事件分发
        $capsule->setEventDispatcher(new EventDispatcher(new Container()));

        // 启动Eloquent
        $capsule->bootEloquent();

        class_alias('\Illuminate\Database\Capsule\Manager', 'DB');
    }

    private function addMysqlConnections(Capsule &$capsule, array $connections)
    {
        foreach ($connections as $name => $connection) {
            $capsule->addConnection([
                'driver' => 'mysql',
                'host' => $connection['host'],
                'port' => $connection['port'],
                'database' => $connection['database'],
                'username' => $connection['username'],
                'password' => $connection['password'],
                'charset' => $connection['charset'],
                'collation' => $connection['collation'],
            ], $name);
        }

        return true;
    }

    private function addMongodbConnections(Capsule &$capsule, array $connections)
    {
        foreach ($connections as $name => $connection) {
            $capsule->addConnection([
                'driver' => 'mongodb',
                'host' => $connection['host'],
                'port' => $connection['port'],
                'username' => $connection['username'],
                'password' => $connection['password'],
                'options' => [
                    "database" => $connection['database']
                ]
            ], $name);
        }

        return true;
    }

    /**
     * @throws \Library\Utils\Redis\Exception\InvalidArgumentException
     */
    public function _initCache()
    {
        $cacheConfig = include joinPaths(APPLICATION_PATH, '/config/cache.php');
        $defaultName = $cacheConfig['default'];

        $connections = $cacheConfig['connections'];

        $cacheManager = new CacheProvider();
        $cacheManager->setDefaultConnectionName($defaultName);
        foreach ($connections as $name => $connection) {
            $cacheManager->addConnection($connection, $name);
        }

        $cacheManager->setAsGlobal();


    }

    /**
     * 初始化Resque队列的链接配置
     */
    public function _initResqueDsn()
    {
        $cacheConfig = include joinPaths(APPLICATION_PATH, '/config/cache.php');
        $defaultName = $cacheConfig['default'];

        $connections = $cacheConfig['connections'];
        $connection = $connections[$defaultName];
        Resque::setBackend("redis://:" . $connections['password'] . "@" . $connections['host'] .":" . $connections['port'] . "/" . $connections['database']);
    }
}