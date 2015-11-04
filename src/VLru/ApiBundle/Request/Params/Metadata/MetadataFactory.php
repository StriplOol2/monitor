<?php

namespace VLru\ApiBundle\Request\Params\Metadata;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Cache;
use VLru\ApiBundle\Request\Params\BaseParam;
use VLru\ApiBundle\Request\ParamsBag;

class MetadataFactory
{
    /** @var Cache */
    private $cache;

    /** @var Reader */
    private $annotationReader;

    /**
     * @param Reader $annotationReader
     */
    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param Cache $cache
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param array $controller
     * @return ActionMetadata
     */
    public function getMetadataFor(array $controller)
    {
        $class = get_class($controller[0]);
        $method = $controller[1];
        $cacheKey = "ActionMetadata({$class}::{$method})";

        if (null !== $this->cache && false !== ($metadata = $this->cache->fetch($cacheKey))) {
            return $metadata;
        }

        $builder = new MetadataBuilder();
        $reflectionMethod = new \ReflectionMethod($class, $method);

        foreach ($this->annotationReader->getMethodAnnotations($reflectionMethod) as $annotation) {
            if ($annotation instanceof BaseParam) {
                $builder->addParamAnnotation($annotation);
            }
        }

        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $class = $reflectionParameter->getClass();
            if (null === $class) {
                continue;
            }

            if ($class->isSubclassOf(ParamsBag::class)) {
                $builder->addParamsBag($reflectionParameter->getName(), $class->getName());
            }
        }

        $metadata = $builder->build();

        if (null !== $this->cache) {
            $this->cache->save($cacheKey, $metadata);
        }

        return $metadata;
    }
}
