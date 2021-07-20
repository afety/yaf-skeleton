<?php

namespace Dao;

use Illuminate\Database\Concerns\ManagesTransactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Eloquent 通过deleted_at字段进行软删，在有字段唯一性的场景上可能存在影响
 * 此时不能通过mysql的unique需要在代码方面进行控制
 * Class MysqlModel
 * @package Dao
 */
class MysqlModel extends Model
{
    use SoftDeletes, ManagesTransactions;

    protected $table;

    /**
     * default mysql connection
     * @var string
     */
    protected $connection = "default";

    /**
     * @var string
     */
    protected $dateFormat = "Y-m-d H:i:s";

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * @return mixed
     */
    public static function getTableName()
    {
        $calledClass = get_called_class();
        return (new $calledClass)->getTable();
    }

    protected static function boot()
    {
        parent::boot();
    }

    /**
     * hasManyThrough远程查询实际sql为inner join
     * 由于多表连接中存在column名重复问题，导致related
     * 的值被through覆盖
     * 因此需要指定select
     * @param string $related
     * @param string $through
     * @param string $firstKey
     * @param string $secondKey
     * @param string $localKey
     * @param string $secondLocalKey
     * @return HasManyThrough
     */
    public function hasManyThrough(
        $related, $through,
        $firstKey = null, $secondKey = null,
        $localKey = null, $secondLocalKey = null): HasManyThrough
    {
        return parent::hasManyThrough($related, $through,
            $firstKey, $secondKey, $localKey, $secondLocalKey)->select($related::getTableName() . ".*");
    }


    /*
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /*
     * @return string
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /*
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /*
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    // Setter Func
    /*
     * @param string $deletedAt
     * @return $this
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deleted_at = $deletedAt;
        return $this;
    }

    /*
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    /*
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }
}