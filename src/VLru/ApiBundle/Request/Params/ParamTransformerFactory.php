<?php

namespace VLru\ApiBundle\Request\Params;

use Symfony\Component\DependencyInjection\ContainerInterface;
use VLru\ApiBundle\Request\Params\Metadata\TransformRule;

class ParamTransformerFactory
{
    /** @var ParamTransformer */
    private $transformers = [];

    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $name
     *
     * @return ParamTransformer
     */
    public function getTransformer($name)
    {
        if (!isset($this->transformers[$name])) {
            if (class_exists($name)) {
                $this->transformers[$name] = new $name();
            } elseif ($this->container->has($name)) {
                $this->transformers[$name] = $this->container->get($name);
            } else {
                throw new \InvalidArgumentException('Class or bean for transformer "'. $name .'" not found."');
            }
        }

        if (!$this->transformers[$name] instanceof ParamTransformer) {
            throw new \UnexpectedValueException(
                'Transformer "'. $name ."' must be instance of ". ParamTransformer::class
            );
        }

        return $this->transformers[$name];
    }

    /**
     * @param TransformRule $rule
     *
     * @return ParamTransformer
     */
    public function getInitializedTransformer(TransformRule $rule)
    {
        $transformer = $this->getTransformer($rule->transformBy);
        $transformer->initialize($rule);

        return $transformer;
    }
}
