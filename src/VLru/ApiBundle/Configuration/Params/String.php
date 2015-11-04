<?php

namespace VLru\ApiBundle\Configuration\Params;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use VLru\ApiBundle\Request\Params\ScalarParam;

/**
 * @Annotation
 * @Target({"METHOD", "ANNOTATION"})
 */
class String extends ScalarParam
{
    /** @var string */
    public $pattern;

    /** @var int */
    public $minLength;

    /** @var int */
    public $maxLength;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        if (null !== $this->pattern) {
            $this->addValueConstraint(new Regex([
                'pattern' => "#^{$this->pattern}$#ixsu",
                'message' => "Value doesn't match pattern '$this->pattern'"
            ]));
        }

        if ((null !== $this->minLength) || (null !== $this->maxLength)) {
            $this->addValueConstraint(new Length([
                'min' => $this->minLength,
                'max' => $this->maxLength
            ]));
        }
    }
}
