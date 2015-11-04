<?php

namespace VLru\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('api');

        $rootNode
            ->children()
                ->scalarNode('cache')->defaultNull()->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
