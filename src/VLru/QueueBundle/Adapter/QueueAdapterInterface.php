<?php

namespace VLru\QueueBundle\Adapter;

use VLru\QueueBundle\Entity\QueueElement;
use VLru\QueueBundle\Exception\SerializerNotFoundException;

interface QueueAdapterInterface
{
    /**
     * @param QueueElement $queueElement
     *
     * @return bool
     * @throws SerializerNotFoundException
     */
    public function publish(QueueElement $queueElement);
}