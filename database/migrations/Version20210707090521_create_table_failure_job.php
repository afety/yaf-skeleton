<?php

declare(strict_types=1);

namespace Database\Migrations;

use Database\MyAbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210707090521_create_table_failure_job extends MyAbstractMigration
{
    protected static $tableName = 'failure_job';

    public function getDescription() : string
    {
        return '失败任务表';
    }

    public static function addColumns(Schema $schema) : Table
    {
        $table = $schema->createTable(self::$tableName);
        $table->addColumn('id', 'integer')
            ->setComment('主键')
            ->setUnsigned(true)
            ->setAutoincrement(true);

        // TODO: add columns
        $table->addColumn('status', 'smallint')
            ->setUnsigned(true)
            ->setNotnull(true)
            ->setDefault(0)
            ->setComment('是否已重新处理 0否 1是');

        $table->addColumn('job_class', 'string')
            ->setNotnull(true)
            ->setComment('Job类');

        $table->addColumn('args', 'string')
            ->setNotnull(true)
            ->setComment('参数');

        $table->addColumn('queue_name', 'string')
            ->setNotnull(true)
            ->setComment('队列名称');

        $table->addColumn('exception_message', 'text')
            ->setNotnull(true)
            ->setDefault('')
            ->setComment('错误提示');

        $table->addColumn('exception_trace', 'text')
            ->setComment('错误请求跟踪');

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