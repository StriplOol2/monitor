<?php

namespace VLru\ApiBundle\Request;

class DatesRangeParam
{
    /** @var \DateTime */
    protected $start;

    /** @var \DateTime */
    protected $end;

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     */
    public function __construct(\DateTime $start = null, \DateTime $end = null)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }
}
