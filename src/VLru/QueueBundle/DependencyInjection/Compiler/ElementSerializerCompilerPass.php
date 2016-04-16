<?php

namespace VLru\QueueBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ElementSerializerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('queue.strategy.element_serializer')) {
            return;
        }

        $remoteClientAdapterCollection = $container->getDefinition('queue.strategy.element_serializer');

        $taggedElement = $container->findTaggedServiceIds('queue.element_serializer');

        foreach ($taggedElement as $id => $tags) {
            $remoteClientAdapterCollection->addMethodCall('addSerializer', [ new Reference($id) ]);
        }
    }
}
