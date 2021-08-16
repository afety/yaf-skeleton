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

    protected $connection = 'mongodb';

    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 解决save delete update等操作触发的异常
     * https://github.com/jenssegers/laravel-mongodb/issues/1238
     * @param null $connection
     * @return Connection|ConnectionInterface
     * @time 2020/10/28 21:19
     */
    public static function resolveConnection($connection = null)
    {
        return static::$resolver->connection('mongodb');
    }

    public function scopeOrderByCreatedAt(Builder $query)
    {
        return $query->orderByDesc('created_at');
    }
}