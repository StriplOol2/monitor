<?php

namespace VLru\ApiBundle\Serialization;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class MappingAwareProxyNameConverter implements NameConverterInterface
{
    /** @var string[] */
    private $mapping;

    /** @var NameConverterInterface */
    private $innerConverter;

    /**
     * @param NameConverterInterface|null $innerConverter
     */
    public function __construct(NameConverterInterface $innerConverter = null)
    {
        $this->mapping = [];
        $this->innerConverter = $innerConverter;
    }

    /**
     * @param \string[] $mapping
     */
    public function setMapping(array $mapping)
    {
        $this->mapping = $mapping;
    }


    /**
     * {@inheritdoc}
     */
    public function normalize($propertyName)
    {
        if (array_key_exists($propertyName, $this->mapping)) {
            $propertyName = $this->mapping[$propertyName];
        }

        if (null !== $this->innerConverter) {
            return $this->innerConverter->normalize($propertyName);
        }

        return $propertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($propertyName)
    {
        if (null !== $this->innerConverter) {
            $propertyName = $this->innerConverter->denormalize($propertyName);
        }

        return array_search($propertyName, $this->mapping) ?: $propertyName;
    }
}
