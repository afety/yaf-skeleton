<?php

declare(strict_types=1);

namespace Database\Migrations;

use Database\MyAbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Migration: Please modify to your needs!
*/
final class Version20210716112914_alter_add_name_to_issue_main extends MyAbstractMigration
{
    protect static $tableName = '';

    public function getDescription() : string
    {
        return '';
    }

    public function execute(Schema $schema)
    {
        // this execute() migration is auto-generated, please modify it to your needs

    }

    public function rollback(Schema $schema)
    {
        // this rollback() migration is auto-generated, please modify it to your needs

    }
}