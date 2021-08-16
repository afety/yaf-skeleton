<?php

namespace Database;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Migrations\AbstractMigration;

abstract class MyAbstractMigration extends AbstractMigration
{
    protected static $tableName = '';

    /**
     *
     *
     * @return string
     */
    public static function getTableName()
    {
        return self::$tableName;
    }

    /**
     *
     *
     * @param Schema $schema
     */
    final public function up(Schema $schema): void
    {
        static::addColumns($schema);
    }

    /**
     * @param Schema $schema
     * @return Table
     *
     *
     */
    abstract static function addColumns(Schema $schema): Table;

    /**
     *
     *
     * @param Schema $schema
     */
    final public function down(Schema $schema): void
    {
        static::rollback($schema);
    }

    /**
     *
     *
     * @param Schema $schema
     * @return mixed
     */
    abstract static function rollback(Schema $schema);
}