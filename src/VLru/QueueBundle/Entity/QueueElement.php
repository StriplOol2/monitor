<?php

namespace VLru\QueueBundle\Entity;

abstract class QueueElement
{
    /** @var string */
    protected $type;

    /** @var \DateTime */
    protected $timeoutAt;

    /**
     * QueueElement constructor.
     *
     * @param string $type
     * @param \DateTime $timeoutAt
     */
    public function __construct($type, \DateTime $timeoutAt)
    {
        $this->type = $type;
        $this->timeoutAt = $timeoutAt;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \DateTime
     */
    public function getTimeoutAt()
    {
        return $this->timeoutAt;
    }

    /**
     * @return bool
     */
    public function isTimeout()
    {
        $now = new \DateTime();
        return $this->getTimeoutAt() < $now;
    }
}