<?php

declare(strict_types=1);

namespace <namespace>;

use Database\MyAbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Migration: Please modify to your needs!
*/
final class Version<version> extends MyAbstractMigration
{
    protect static $tableName = '';

    public function getDescription() : string
    {
        return '';
    }

    public function execute(Schema $schema)
    {
        // this execute() migration is auto-generated, please modify it to your needs
        <up>
    }

    public function rollback(Schema $schema)
    {
        // this rollback() migration is auto-generated, please modify it to your needs
        <down>
    }
}