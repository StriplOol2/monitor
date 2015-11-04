<?php

namespace VLru\ApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use VLru\ApiBundle\DependencyInjection\Compiler\ParamsConverterCompilerPass;
use VLru\ApiBundle\DependencyInjection\Compiler\SerializerConfigurationCompilerPass;

class ApiBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ParamsConverterCompilerPass());
        $container->addCompilerPass(new SerializerConfigurationCompilerPass());
    }
}
