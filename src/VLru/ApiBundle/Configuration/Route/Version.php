<?php

namespace VLru\ApiBundle\Configuration\Route;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Version
{
    /** @var int */
    protected $majorFrom;

    /** @var int */
    protected $majorTo;

    /** @var int */
    protected $minorFrom;

    /** @var int */
    protected $minorTo;

    /**
     * @param $options
     */
    public function __construct($options)
    {
        if (isset($options['value'])) {
            throw new \InvalidArgumentException('Property value does not exist');
        }

        $versionCorrect = false;

        if (isset($options['from'])) {
            $data = explode('.', $options['from']);
            $this->majorFrom = (int) $data[0];
            $this->minorFrom = (int) $data[1];
            $versionCorrect = true;
        }

        if (isset($options['to'])) {
            $data = explode('.', $options['to']);
            $this->majorTo = (int) $data[0];
            $this->minorTo = (int) $data[1];
            $versionCorrect = true;
        }

        if (!$versionCorrect) {
            throw new \InvalidArgumentException('Need set parameter from or to');
        }
    }

    /**
     * @return int
     */
    public function getMajorFrom()
    {
        return $this->majorFrom;
    }

    /**
     * @return int
     */
    public function getMajorTo()
    {
        return $this->majorTo;
    }

    /**
     * @return int
     */
    public function getMinorFrom()
    {
        return $this->minorFrom;
    }

    /**
     * @return int
     */
    public function getMinorTo()
    {
        return $this->minorTo;
    }
}
