<?php

namespace VLru\ApiBundle\Serialization;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Mapping\AttributeMetadataInterface;
use Symfony\Component\Serializer\Mapping\ClassMetadataInterface;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;
use VLru\ApiBundle\Configuration\Serialization\Groups as ExtendedGroups;

class ExtendedMetadataAnnotationLoader implements LoaderInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata)
    {
        $reflectionClass = $classMetadata->getReflectionClass();
        $className = $reflectionClass->name;
        $loaded = false;

        $attributesMetadata = $classMetadata->getAttributesMetadata();

        foreach ($reflectionClass->getProperties() as $property) {
            if (!isset($attributeMetadata[$property->name])) {
                $attributesMetadata[$property->name] = new ExtendedAttributeMetadata($property->name);
                $classMetadata->addAttributeMetadata($attributesMetadata[$property->name]);
            }

            if ($property->getDeclaringClass()->name === $className) {
                foreach ($this->reader->getPropertyAnnotations($property) as $groups) {
                    if ($groups instanceof Groups) {
                        $this->addAnnotationToMeta($attributesMetadata[$property->name], $groups);
                    }

                    $loaded = true;
                }
            }
        }

        foreach ($reflectionClass->getMethods() as $method) {
            if ($method->getDeclaringClass()->name === $className) {
                foreach ($this->reader->getMethodAnnotations($method) as $groups) {
                    if ($groups instanceof Groups) {
                        if (preg_match('/^(get|is|has|set)(.+)$/i', $method->name, $matches)) {
                            $attributeName = lcfirst($matches[2]);

                            if (isset($attributesMetadata[$attributeName])) {
                                $attributeMetadata = $attributesMetadata[$attributeName];
                            } else {
                                $attributeMetadata = new ExtendedAttributeMetadata($attributeName);
                                $attributesMetadata[$attributeName] = $attributeMetadata;
                                $classMetadata->addAttributeMetadata($attributeMetadata);
                            }

                            $this->addAnnotationToMeta($attributeMetadata, $groups);
                        } else {
                            throw new MappingException(sprintf(
                                'Groups on "%s::%s" cannot be added. '.
                                'Groups can only be added on methods beginning with "get", "is", "has" or "set".',
                                $className,
                                $method->name
                            ));
                        }
                    }

                    $loaded = true;
                }
            }
        }

        return $loaded;
    }

    /**
     * @param AttributeMetadataInterface $attrMeta
     * @param Groups $annotation
     */
    private function addAnnotationToMeta(AttributeMetadataInterface $attrMeta, Groups $annotation)
    {
        $isExtended = $annotation instanceof ExtendedGroups && $attrMeta instanceof ExtendedAttributeMetadata;
        foreach ($annotation->getGroups() as $group) {
            if ($isExtended) {
                $attrMeta->addGroup($group, $annotation->getMapping());
            } else {
                $attrMeta->addGroup($group);
            }
        }
    }
}
