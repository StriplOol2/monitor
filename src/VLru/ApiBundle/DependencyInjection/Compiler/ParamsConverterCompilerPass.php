<?php

namespace VLru\ApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ParamsConverterCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('api.converter.manager')) {
            return;
        }

        $manager = $container->getDefinition('api.converter.manager');

        $taggedServices = $container->findTaggedServiceIds('params.converter');
        foreach ($taggedServices as $id => $tags) {
            $manager->addMethodCall('add', [ new Reference($id) ]);
        }
    }
}
