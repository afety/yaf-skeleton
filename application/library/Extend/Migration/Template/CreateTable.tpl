<?php

declare(strict_types=1);

namespace <namespace>;

use Database\MyAbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version<version> extends MyAbstractMigration
{
    protected static $tableName = '<tableName>';

    public function getDescription() : string
    {
        return '';
    }

    public static function addColumns(Schema $schema) : Table
    {
        $table = $schema->createTable(self::$tableName);
        $table->addColumn('id', 'integer')
            ->setComment('主键')
            ->setUnsigned(true)
            ->setAutoincrement(true);

        // TODO: add columns


        $table->addColumn('deleted_at', 'datetime')
            ->setNotnull(false)
            ->setComment('删除时间');

        $table->addColumn('updated_at', 'datetime')
            ->setNotnull(true)
            ->setDefault(date('Y-m-d H:i:s'))
            ->setComment('更新时间');

        $table->addColumn('created_at', 'datetime')
            ->setNotnull(true)
            ->setDefault(date('Y-m-d H:i:s'))
            ->setComment('创建时间');

        $table->setPrimaryKey(['id']);

        return $table;
    }

    public static function rollback(Schema $schema)
    {
        if ($schema->hasTable(self::$tableName)) {
            $schema->dropTable(self::$tableName);
        }
    }
}