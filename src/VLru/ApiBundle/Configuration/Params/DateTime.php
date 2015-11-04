<?php

namespace VLru\ApiBundle\Configuration\Params;

use Symfony\Component\Validator\Constraints\Type;
use VLru\ApiBundle\Request\Params\ScalarParam;

/**
 * @Annotation
 * @Target({"METHOD", "ANNOTATION"})
 */
class DateTime extends ScalarParam
{
    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        $this->addSourceConstraint(
            new Type(['type' => 'digit', 'message' => 'Field must be unsigned int'])
        );
    }
}
