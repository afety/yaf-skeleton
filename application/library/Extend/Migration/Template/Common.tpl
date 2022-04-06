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
    protected static $tableName = '';

    public function getDescription() : string
    {
       return '';
    }

    public static function addColumns(Schema $schema): Table
    {
        // this execute() migration is auto-generated, please modify it to your needs
        <up>
    }

    public static function rollback(Schema $schema)
    {
        // this rollback() migration is auto-generated, please modify it to your needs
        <down>
    }
}