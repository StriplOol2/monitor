<?php

namespace VLru\QueueBundle\Producer;

interface QueueProducerInterface
{
    /**
     * @param array $bean
     *
     * @return bool
     */
    public function addToQueue(array $bean);
}