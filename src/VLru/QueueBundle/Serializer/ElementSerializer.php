<?php

namespace VLru\QueueBundle\Serializer;

use VLru\QueueBundle\Entity\QueueElement;
use VLru\QueueBundle\Factory\QueueElementFactory;

abstract class ElementSerializer
{
    /**
     * @var QueueElementFactory
     */
    protected $factory;

    /**
     * CacheUpdateElementSerializer constructor.
     *
     * @param QueueElementFactory $factory
     */
    public function __construct(QueueElementFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    abstract public function valid($type);

    /**
     * @param QueueElement $queueElement
     *
     * @return array
     */
    public function serialize(QueueElement $queueElement)
    {
        $result = [];

        $result['type'] = $queueElement->getType();
        $result['timeout_timestamp'] = $queueElement->getTimeoutAt()->getTimestamp();

        return $result + $this->abstractChildSerialize($queueElement);
    }

    /**
     * @param array $bean
     *
     * @return QueueElement
     */
    public function deserialize(array $bean)
    {
        return $this->factory->create($bean);
    }

    /**
     * @param QueueElement $queueElement
     *
     * @return array
     */
    abstract protected function abstractChildSerialize(QueueElement $queueElement);
}