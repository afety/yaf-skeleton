<?php

namespace Library\Extend\Migration;

use Doctrine\Migrations\Tools\Console\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends AbstractCommand
{
    protected static $defaultName = 'migrations:generate';

    /**
     *
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        if (isset($input->getOptions()['create'])) return $this->generateCreate($input, $output);
        else if (isset($input->getOptions()['alter'])) return $this->generateAlter($input, $output);
        else {
            $output->writeln('use -help option look up usage!');
        }
        return -1;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    private function generateCreate(InputInterface $input, OutputInterface $output)
    {
        $tableName = $input->getOption('create');

        if (!preg_match('/^[a-zA-Z_]+$/', $tableName)) {
            echo "Invalid Table Name\n";
            return -1;
        }

        $this->configuration->setCustomTemplate(joinPaths(MIGRATION_FILE_TEMPLATE_DIR, 'CreateTable.tpl'));

        $versionNumber = $this->configuration->generateVersionNumber();
        $filepath = $versionNumber . "_create_table_" . $tableName;
        $path = $this->_execute($input, $output, $filepath);

        // 替换模板的<tableName>
        $sqlFileStr = file_get_contents($path);
        $sqlFileStr = str_replace('<tableName>', $tableName, $sqlFileStr);
        file_put_contents($path, $sqlFileStr);

        return 0;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $filepath
     * @return string
     *
     *
     */
    private function _execute(InputInterface $input, OutputInterface $output, string $filepath): string
    {
        $migrationGenerator = $this->dependencyFactory->getMigrationGenerator();

        $path = $migrationGenerator->generateMigration($filepath);

        $output->writeln([
            sprintf('Generated new migration class to "<info>%s</info>"', $path),
            '',
        ]);

        return $path;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     *
     *
     */
    private function generateAlter(InputInterface $input, OutputInterface $output)
    {
        $desc = $input->getOption('alter');
        if (!preg_match('/^[a-zA-Z_]+$/', $desc)) {
            echo "Invalid Characters\n";
            die();
        }

        $this->configuration->setCustomTemplate(joinPaths(MIGRATION_FILE_TEMPLATE_DIR, 'Common.tpl'));

        $versionNumber = $this->configuration->generateVersionNumber();
        $filepath = $versionNumber . '_alter_' . $desc;

        return $this->_execute($input, $output, $filepath);
    }

    /**
     *
     *
     */
    protected function configure(): void
    {
        $this->setAliases(['generate'])
            ->setDescription('生成迁移脚本')
            ->addOption(
                'create',
                '--c',
                InputOption::VALUE_REQUIRED,
                '传入需要创建的表名'
            )
            ->addOption(
                'alter',
                '--a',
                InputOption::VALUE_REQUIRED,
                '传入描述 英文'
            )
            ->setHelp(<<<EOT
The <info>%command.name%</info> command generates a migration class:
    <info>%command.full_name%</info>
You can optionally specify a <comment>--create</comment> option to generate create table file 
You can optionally specify a <comment>--alter</comment> option to generate file which alter table
EOT
            );

        parent::configure();
    }
}