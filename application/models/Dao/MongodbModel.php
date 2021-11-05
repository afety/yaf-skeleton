<?php

namespace Dao;

use Illuminate\Database\Concerns\ManagesTransactions;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class MongodbAbstract extends Model
{
    use SoftDeletes, ManagesTransactions;

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * 原始链接名称变量名为connection
     * Jenssegers\Mongodb\Eloquent\Model 继承 illuminate\database\Eloquent\Model
     * 会将connection置为null，因此需要变更为其他名称，且提供getConnectionName方法
     */
    protected $connectionName = 'mongodb';

    public function getConnectionName()
    {
        return $this->connectionName;
    }

    /**
     * 解决save delete update等操作触发的异常
     * https://github.com/jenssegers/laravel-mongodb/issues/1238
     * @param null $connection
     * @return Connection|ConnectionInterface
     * @time 2020/10/28 21:19
     */
    public static function resolveConnection($connection = null)
    {
        return static::$resolver->connection($connection);
    }

    public function scopeOrderByCreatedAt(Builder $query)
    {
        return $query->orderByDesc('created_at');
    }
}