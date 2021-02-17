<?php


use Codeception\Events;
use Codeception\Extension;
use Codeception\Module\Cli;

class DatabaseMigrationExtension extends Extension
{
    public static $events = [
        Events::SUITE_BEFORE => ['beforeSuite', 1000],
    ];

    public function beforeSuite()
    {
        $server = $_SERVER;
        $env = $_ENV;
        unset($_SERVER, $_ENV);

        try {
            /** @var Cli $cli */
            $cli = $this->getModule('Cli');
            $envName = $_ENV['APP_ENV'] ?? 'test';

            $this->writeln('Recreating the DB...');
            $cli->runShellCommand('bin/console doctrine:database:drop --if-exists --force --env=' . $envName);
            $cli->seeResultCodeIs(0);
            $cli->runShellCommand('bin/console doctrine:database:create --env=' . $env);
            $cli->seeResultCodeIs(0);

            $this->writeln('Running Doctrine Migrations...');
            $cli->runShellCommand('bin/console doctrine:migrations:migrate --no-interaction --env=' . $envName);
            $cli->seeResultCodeIs(0);

            $this->writeln('Test database recreated');
        } catch (\Exception $e) {
            $this->writeln(
                sprintf(
                    'An error occurred whilst rebuilding the test database: %s',
                    $e->getMessage()
                )
            );
        }
    }
}