<?php

namespace VLru\QueueBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use VLru\CommonBundle\Helpers\ArrayHelper;
use VLru\QueueBundle\Entity\Acknowledge;
use VLru\QueueBundle\Entity\QueueElement;
use VLru\QueueBundle\Strategy\ElementSerializeStrategy;

abstract class AbstractConsumer implements ConsumerInterface
{
    /** @var ElementSerializeStrategy */
    protected $elementSerializeStrategy;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * ConsumerDecorator constructor.
     *
     * @param ElementSerializeStrategy $elementSerializeStrategy
     * @param LoggerInterface $logger
     */
    public function __construct(
        ElementSerializeStrategy $elementSerializeStrategy,
        LoggerInterface $logger
    ) {
        $this->elementSerializeStrategy = $elementSerializeStrategy;
        $this->logger = $logger;
    }

    /**
     * @param AMQPMessage $msg
     *
     * @return bool|mixed
     */
    public function execute(AMQPMessage $msg)
    {
        try {
            $message = @json_decode($msg->body, true);
            if (!$message) {
                $this->logger->error('Cannot decode message', [
                    'message' => $msg->body
                ]);
                return $this->prepareAcknowledge(Acknowledge::REJECT_FROM_QUEUE);
            }
            $type = ArrayHelper::value($message, 'type');
            if (null === $type) {
                $this->logger->error('Type not found');
                return $this->prepareAcknowledge(Acknowledge::REJECT_FROM_QUEUE);
            }

            $elementSerializer = $this->elementSerializeStrategy->getByElementType($type);
            $element = $elementSerializer->deserialize($message);

            if ($element->isTimeout()) {
                $this->logger->info('Start process timeout part', ['element' => @serialize($element)]);
                return $this->prepareAcknowledge($this->runTimeoutProcessPart($element));
            }
            $this->logger->info('Start process mandatory part', ['element' => @serialize($element)]);
            return $this->prepareAcknowledge($this->runMandatoryProcessPart($element));
        } catch (\Exception $e) {
            $this->logger->error('Unexpected error while execute consumer', [
                'exception' => $e
            ]);
            return $this->prepareAcknowledge(Acknowledge::RE_QUEUE);
        }
    }

    /**
     * @param $acknowledge
     *
     * @return mixed
     */
    protected function prepareAcknowledge($acknowledge)
    {
        if (!$acknowledge) {
            $this->logger->error("Process part ended with error");
        } else {
            $this->logger->info("Process part success ended");
        }

        usleep(500000);
        return $acknowledge;
    }

    /**
     * @param QueueElement $queueElement
     *
     * @return bool
     */
    abstract protected function runMandatoryProcessPart(QueueElement $queueElement);

    /**
     * @param QueueElement $queueElement
     *
     * @return bool
     */
    abstract protected function runTimeoutProcessPart(QueueElement $queueElement);
}