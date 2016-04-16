<?php

namespace VLru\QueueBundle\Producer;

use Psr\Log\LoggerInterface;
use VLru\QueueBundle\Adapter\QueueAdapterInterface;
use VLru\QueueBundle\Factory\QueueElementFactory;

class QueueProducer implements QueueProducerInterface
{
    /** @var QueueAdapterInterface */
    protected $adapter;

    /** @var QueueElementFactory */
    protected $elementFactory;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * RmqQueueProvider constructor.
     *
     * @param QueueAdapterInterface $adapter
     * @param QueueElementFactory $elementFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        QueueAdapterInterface $adapter,
        QueueElementFactory $elementFactory,
        LoggerInterface $logger
    ) {
        $this->adapter = $adapter;
        $this->elementFactory = $elementFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function addToQueue(array $bean)
    {
        try {
            $element = $this->elementFactory->create($bean);
            return $this->adapter->publish($element);
        } catch (\Exception $e) {
            $this->logger->error("Cannot publish message to queue", [
                'exception' => $e,
                'bean' => @json_encode($bean)
            ]);
            return false;
        }
    }
}