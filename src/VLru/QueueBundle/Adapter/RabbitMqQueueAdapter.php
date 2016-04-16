<?php

namespace VLru\QueueBundle\Adapter;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use VLru\QueueBundle\Entity\QueueElement;
use VLru\QueueBundle\Strategy\ElementSerializeStrategy;

class RabbitMqQueueAdapter implements QueueAdapterInterface
{
    /** @var ProducerInterface */
    protected $producer;

    /** @var ElementSerializeStrategy */
    protected $elementSerializeStrategy;

    /**
     * RabbitMqQueueAdapter constructor.
     *
     * @param ProducerInterface $producer
     * @param ElementSerializeStrategy $elementSerializeStrategy
     */
    public function __construct(ProducerInterface $producer, ElementSerializeStrategy $elementSerializeStrategy)
    {
        $this->producer                 = $producer;
        $this->elementSerializeStrategy = $elementSerializeStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(QueueElement $queueElement)
    {
        $elementSerializer = $this->elementSerializeStrategy->getByElementType($queueElement->getType());
        $this->producer->publish(\json_encode($elementSerializer->serialize($queueElement)));

        return true;
    }
}