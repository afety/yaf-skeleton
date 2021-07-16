<?php

namespace Library\Extend\Migration;

use Database\MyAbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Migrations\Tools\Console\Command\AbstractCommand;
use ReflectionClass;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 通过迁移文件生成Model
 * Class GenerateModelCommand
 * @package Extend\Migration
 */
class GenerateModelCommand extends AbstractCommand
{
    protected const SETTER_PREFIX = 'set';
    protected const GETTER_PREFIX = 'get';
    protected static $defaultName = 'migrations:generateModel';
    protected static $setterTemplate =
        '
    public function <funcname>(<columnType> $<varableName>)
    {
        $this-><columnName> = $<varableName>;
        return $this;
    }';

    protected static $getterTemplate =
        '
    public function <funcname>()
    {
        return $this-><columnName>;
    }';

    protected function configure(): void
    {
        $this->setAliases(['generateModel'])
            ->setDescription('根据迁移脚本生成Model类')
            ->addOption(
                'class',
                '',
                InputOption::VALUE_REQUIRED,
                '传入迁移文件名称 无需后缀'
            )
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> command generates a migration class:
    <info>%command.full_name%</info>
You can optionally specify a <comment>--class</comment> option to specific which migration file 
EOT
            );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrationFilename = $input->getOption('class');

        if (empty($migrationFilename)) {
            $output->writeln('使用 --class 指定迁移文件名称');
            return -1;
        }

        $filepath = joinPaths(MIGRATION_FILE_DIR, $migrationFilename . '.php');
        if (!file_exists($filepath)) {
            $output->writeln("Cannot find file: " . $filepath);
            return -1;
        }

        if (!is_file($filepath)) {
            $output->writeln('Not a regular file: ' . $filepath);
        }

        $migrationClass = $this->configuration->getMigrationsNamespace() . '\\' . $migrationFilename;
        $reflect = new ReflectionClass($migrationClass);

        if (
            $reflect->getParentClass() === false ||
            $reflect->getParentClass()->getName() !== MyAbstractMigration::class
        ) {
            $output->writeln('migration class not extend MyAbstractMigration');
            return -1;
        }

        $table = $migrationClass::addColumns(new Schema());
        assert($table instanceof Table);

        $namespace = DAO_NAMESPACE;
        $tableName = $table->getName();
        $modelName = ucfirst(snakeToCamelCase($tableName));
        $parentClassName = DAO_MYSQL_MODEL_BASE_CLASS;

        $columns = [];
        $defaultColumns = [
            'id', 'deleted_at', 'created_at', 'updated_at'
        ];

        foreach ($table->getColumns() as $column) {
            $columnParser = new ColumnParser($column);

            if (in_array($columnParser->getColumnName(), $defaultColumns)) continue;

            $columns['fillable'][] = $columnParser->getColumnName();
            $columns['getters'][] = $columnParser->generateGetterFuncStr();
            $columns['setters'][] = $columnParser->generateSetterFuncStr();
            $columns['comments'][] = $columnParser->getFieldCommentStr();
        }

        $fillable = "'" . implode("', '", $columns['fillable'] ?? []) . "'";

        $setter = implode("\n", $columns['setters'] ?? []);
        $getter = implode("\n", $columns['getters'] ?? []);
        $fieldsComment = implode("\n        ", $columns['comments'] ?? []);

        $modelPath = joinPaths(DAO_DIR, $modelName . ".php");

        $tplStr = file_get_contents(joinPaths(MIGRATION_FILE_TEMPLATE_DIR, "Model.tpl"));

        $tplStr = str_replace(
            [
                '<namespace>',
                '<modelName>',
                '<parentClassName>',
                '<tableName>',
                '<fillable>',
                '<fieldsComment>',
                '<getterZone>',
                '<setterZone>',
            ],
            [
                $namespace,
                $modelName,
                $parentClassName,
                $tableName,
                $fillable,
                $fieldsComment,
                $getter,
                $setter
            ],
            $tplStr
        );

        file_put_contents($modelPath, $tplStr);

        return 0;
    }
}