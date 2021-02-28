<?php

namespace TripBundle\Tests\Api\app;

use ApiPlatform\Core\Bridge\Symfony\Bundle\ApiPlatformBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use TripBundle\TripBundle;
use Zenstruck\Foundry\ZenstruckFoundryBundle;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', false);
    }

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new DoctrineMigrationsBundle(),
            new TwigBundle(),
            new SecurityBundle(),
            new DoctrineFixturesBundle(),
            new ZenstruckFoundryBundle(),
            new ApiPlatformBundle(),
            new MonologBundle(),
            new TripBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $c->loadFromExtension('framework',
            [
                'secret' => 'F00',
                'test' => true,
            ])->loadFromExtension('doctrine',
            [
                'dbal' => [
                    'url' => '%env(resolve:DATABASE_URL)%',
                ],
                'orm' => [
                    'entity_managers' => [
                        'default' => [
                            'naming_strategy' => 'doctrine.orm.naming_strategy.underscore',
                        ],
                    ],
                ],
            ])->loadFromExtension('api_platform',
            [
                'path_segment_name_generator' => 'api_platform.path_segment_name_generator.dash',
            ]);
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->import(__DIR__.'/../../../Resources/config/routes.yaml');
    }
}
