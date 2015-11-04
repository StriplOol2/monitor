<?php

namespace VLru\ApiBundle\Configuration\Params;

use Symfony\Component\Validator\Constraints\Range;
use VLru\ApiBundle\Request\Params\ScalarParam;
use VLru\ApiBundle\Validation\Constraint\IsNumber;

/**
 * @Annotation
 * @Target({"METHOD", "ANNOTATION"})
 */
class Integer extends ScalarParam
{
    /** @var int */
    public $min;

    /** @var int */
    public $max;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        $this->addSourceConstraint(new IsNumber());

        if ((null !== $this->min) || (null !== $this->max)) {
            $this->addValueConstraint(new Range([
                'min' => $this->min,
                'max' => $this->max
            ]));
        }
    }
}
