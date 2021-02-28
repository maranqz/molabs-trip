<?php

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;
use TripBundle\Tests\Api\app\AppKernel;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    try {
        (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
    } catch (PathException $e) {
    }
}

function dbInit()
{
    $kernel = new AppKernel();
    $kernel->boot();

    $application = new Application($kernel);
    $application->setAutoExit(false);

    $application->run(new ArrayInput([
        'command' => 'doctrine:database:create',
        '--if-not-exists' => 1,
    ]));

    $kernel->shutdown();
}

dbInit();
