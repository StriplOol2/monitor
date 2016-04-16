<?php

namespace VLru\QueueBundle\Factory;

use VLru\QueueBundle\Entity\QueueElement;

abstract class QueueElementFactory
{
    /**
     * @param array $bean
     *
     * @return QueueElement
     */
    abstract public function create(array $bean);
}