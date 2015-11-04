<?php

namespace VLru\ApiBundle\Serialization;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @property MappingAwareProxyNameConverter $nameConverter
 */
class MappingAwareObjectNormalizer extends ObjectNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyAccessorInterface $propertyAccessor = null
    ) {
        $nameConverter = new MappingAwareProxyNameConverter($nameConverter);
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor);
    }

    /**
     * {@inheritdoc}
     */
    protected function getAllowedAttributes($classOrObject, array $context, $attributesAsString = false)
    {
        if (!$this->classMetadataFactory || !isset($context['groups']) || !is_array($context['groups'])) {
            return false;
        }

        $allowedAttributes = [];
        $attributesMapping = [];

        $attributesMetadata = $this->classMetadataFactory->getMetadataFor($classOrObject)->getAttributesMetadata();
        foreach ($attributesMetadata as $meta) {
            $matchGroups = array_intersect($context['groups'], $meta->getGroups());
            if (count($matchGroups)) {
                $allowedAttributes[] = $attributesAsString ? $meta->getName() : $meta;
                if ($meta instanceof ExtendedAttributeMetadata) {
                    $attributesMapping[$meta->getName()] = $meta->getMapping($matchGroups);
                } else {
                    $attributesMapping[$meta->getName()] = $meta->getName();
                }
            }
        }

        $this->nameConverter->setMapping($attributesMapping);
        return array_unique($allowedAttributes);
    }
}
