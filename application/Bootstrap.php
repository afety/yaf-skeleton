<?php

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher as EventDispatcher;
use Jenssegers\Mongodb\Connection;
use Library\Exceptions\MyErrorException;
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

    // 加载常量目录
    public function _initConstant()
    {
        define('APPLICATION_CONSTANT_DIR', joinPaths(APPLICATION_PATH, 'constant'));
        foreach (scandir(APPLICATION_CONSTANT_DIR) as $filename) {
            $path = joinPaths(APPLICATION_CONSTANT_DIR, $filename);
            if (is_dir($path)) continue;

            include_once $path;
        }
    }

    /**
     * 初始化Eloquent 数据库链接
     */
    public function _initEloquent()
    {
        $capsule = new Capsule();

        // 从配置中获取数据库连接设置
        $databaseConfig = include joinPaths(APPLICATION_CONSTANT_DIR, 'database.php');

        $defaultConnectionName = $databaseConfig['default'] ?? 'mysql';
        $connections = $databaseConfig['connections'];

        foreach ($connections as $name => $connections) {
            if ($name == 'mysql') {
                // add MySQL connections
                $this->addMysqlConnections($capsule, $connections);
            } else if ($name == 'mongodb') {
                // add MongoDB connections
                $this->addMongodbConnections($capsule, $connections);
            } else if ($name == 'redis') {
                // add Redis connections
                $this->addRedisConnections($capsule, $connections);
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

    private function addRedisConnections(Capsule &$capsule, array $connections)
    {
        
    }
}