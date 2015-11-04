<?php

namespace VLru\ApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use VLru\ApiBundle\Serialization\MappingAwareObjectNormalizer;

/**
 * Здесь мы переопределям стандартные ObjectNormalizer и ChainLoader.
 *
 * Наш MappingAwareObjectNormalizer полностью обратно-совместим с предыдущим
 * поэтому, чтобы избежать сложной конфигурации DI и дополнительной
 * сборки и загрузки стандартного нормалайзера (который никогда не будет вызван
 * т.к. наш нормалайзер будет все перехватывать), было решено просто
 * переопределить класс в зависимости.
 *
 * В serializer.mapping.chain_loader был добавлен ExtendedMetadataAnnotationLoader.
 * Он поддеживает как стандартную, так и нашу/расширенную @Groups аннотацию,
 * поэтому мы или заменяем стандартный AnnotationLoader, либо насильно подсовываем наш,
 * чтобы вся метадата была объектом класса ExtendedAttributeMetadata
 */
class SerializerConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Если сериализатор не одключен, то ничего не делаем
        if (!$container->hasDefinition('serializer')) {
            return;
        }

        $chainLoader = $container->getDefinition('serializer.mapping.chain_loader');
        $loaders = $chainLoader->getArgument(0);
        if (isset($loaders[0]) &&
            $loaders[0] instanceof Definition &&
            $loaders[0]->getClass() === AnnotationLoader::class
        ) {
            $loaders[0] = new Reference('api.serializer.metadata_loader.extended_annotation');
        } else {
            array_unshift($loaders, new Reference('api.serializer.metadata_loader.extended_annotation'));
        }
        $chainLoader->replaceArgument(0, $loaders);

        $normalizer = $container->getDefinition('serializer.normalizer.object');
        $normalizer->setClass(MappingAwareObjectNormalizer::class);
        $normalizer->replaceArgument(1, new Reference('api.serializer.name_converter'));
    }
}
