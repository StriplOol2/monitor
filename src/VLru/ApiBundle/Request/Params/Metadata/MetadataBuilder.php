<?php

namespace VLru\ApiBundle\Request\Params\Metadata;

use Symfony\Component\Validator\Constraints\Composite;
use VLru\ApiBundle\Request\Params\BaseParam;

class MetadataBuilder
{
    /** @var ActionMetadata */
    private $metadata;

    public function __construct()
    {
        $this->metadata = new ActionMetadata();
    }

    /**
     * @param BaseParam $param
     * @return $this
     */
    public function addParamAnnotation(BaseParam $param)
    {
        $paramName = $param->getName();
        if (array_key_exists($paramName, $this->metadata->transformRules)) {
            throw new \InvalidArgumentException('Several params annotation for same value key');
        }

        $this->metadata->transformRules[$paramName] = $param->getTransformRule();
        $this->metadata->valueConstraints[$paramName] = $param->getValueConstraints();

        foreach ($param->getSourceConstraints() as $key => $constraints) {
            if (array_key_exists($key, $this->metadata->sourceConstraints)) {
                throw new \InvalidArgumentException('Several params annotation for same source key');
            }
            $this->metadata->sourceConstraints[$key] = $constraints;

            foreach ($constraints as $constraint) {
                if ($constraint instanceof Composite) {
                    $this->metadata->arraySources[] = $key;
                    break;
                }
            }
        }

        if (null !== $param->getConvertTo()) {
            $this->metadata->valueConverters[$paramName] = $param->getConvertTo();
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $className
     * @return $this
     */
    public function addParamsBag($name, $className)
    {
        $this->metadata->paramsBags[$name] = $className;

        return $this;
    }

    /**
     * @return ActionMetadata
     */
    public function build()
    {
        return $this->metadata;
    }
}
