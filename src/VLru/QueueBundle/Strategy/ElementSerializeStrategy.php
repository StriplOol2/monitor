<?php

namespace VLru\QueueBundle\Strategy;

use VLru\QueueBundle\Exception\SerializerNotFoundException;
use VLru\QueueBundle\Serializer\ElementSerializer;

class ElementSerializeStrategy
{
    /**
     * @var ElementSerializer[]
     */
    protected $serializers;

    public function addSerializer($serializer)
    {
        $this->serializers[] = $serializer;
    }

    /**
     * @param string $type
     *
     * @return ElementSerializer
     * @throws SerializerNotFoundException
     */
    public function getByElementType($type)
    {
        foreach ($this->serializers as $serializer) {
            if ($serializer->valid($type)) {
                return $serializer;
            }
        }

        throw new SerializerNotFoundException();
    }
}