<?php

namespace VLru\ApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class ApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'api';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        if (!$container->hasParameter('build_num')) {
            $container->setParameter('build_num', substr(md5(realpath(__DIR__)), 1, 6));
        }
        $loader->load('services.yml');
        $apiConfig = $this->processConfiguration(new Configuration(), $configs);

        $this->prepareMetadataFactory($apiConfig, $container);
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function prepareMetadataFactory(array $config, ContainerBuilder $container)
    {
        if (null !== $config['cache']) {
            $factory = $container->getDefinition('api.params.metadata_factory');
            $factory->addMethodCall('setCache', [ new Reference($config['cache']) ]);
        }
    }
}
