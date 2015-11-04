<?php

namespace VLru\ApiBundle\Serialization;

use Symfony\Component\Serializer\Mapping\AttributeMetadata;
use Symfony\Component\Serializer\Mapping\AttributeMetadataInterface;

class ExtendedAttributeMetadata extends AttributeMetadata
{
    /**
     * @param string $group
     * @param string|bool $mapping
     */
    public function addGroup($group, $mapping = null)
    {
        if (!array_key_exists($group, $this->groups)) {
            $this->groups[$group] = $mapping;
        }
    }

    /**
     * @return string[]
     */
    public function getGroups()
    {
        return array_keys($this->groups);
    }

    /**
     * @param AttributeMetadataInterface $attributeMetadata
     */
    public function merge(AttributeMetadataInterface $attributeMetadata)
    {
        if ($attributeMetadata instanceof ExtendedAttributeMetadata) {
            foreach ($attributeMetadata->groups as $group => $mapping) {
                $this->addGroup($group, $mapping);
            }
        } else {
            foreach ($attributeMetadata->getGroups() as $group) {
                $this->addGroup($group);
            }
        }
    }

    /**
     * @param string[] $groups
     * @return string
     */
    public function getMapping(array $groups)
    {
        foreach ($groups as $group) {
            if (array_key_exists($group, $this->groups)) {
                return $this->groups[$group] ?: $this->getName();
            }
        }
        return $this->getName();
    }
}
