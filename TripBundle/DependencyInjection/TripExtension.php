<?php

namespace TripBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class TripExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = $this->getLoader($container);
        $loader->load('services.yaml');
    }

    /**
     * @throws ServiceNotFoundException
     */
    public function prepend(ContainerBuilder $container): void
    {
        $doctrineConfig = $container->getExtensionConfig('doctrine_migrations');
        $container->prependExtensionConfig(
            'doctrine_migrations',
            [
                'migrations_paths' => \array_merge(
                    \array_pop($doctrineConfig)['migrations_paths'] ?? [],
                    [
                        'TripBundle\Migrations' => '@TripBundle/Migrations',
                    ]
                ),
            ]
        );

        $loader = $this->getLoader($container);

        $loader->import('{packages}/*.yaml');
    }

    protected function getLoader(ContainerBuilder $container)
    {
        return new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
    }
}
