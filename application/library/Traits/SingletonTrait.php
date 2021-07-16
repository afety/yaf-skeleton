<?php

namespace Library\Traits;

/**
 * 单例
 * Trait Singleton
 * @package Traits
 */
trait SingletonTrait
{
    protected static $_instance = null;

    final private function __construct()
    {
        $this->init();
    }

        protected function init()
    {
    } //  防止反序列化获取多个对象

        /**
     * 单例获取 如果需要修改单例内部参数 则此类就不应该用单例模式
     *
     *
     * @return static
     */
    final public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new static;
        }

        return self::$_instance;
    } // 防止clone产生多个对象

final private function __wakeup()
    {
    }

    // 初始化函数

final private function __clone()
    {
    }

    final private function __sleep()
    {
        return []; // 置空  防止序列化反序列化获取新对象
    }
}