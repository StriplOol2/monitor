<?php

namespace VLru\ApiBundle\Configuration\Params;

use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use VLru\ApiBundle\Request\Params\BaseParam;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class DatesRange extends BaseParam
{
    const PARAM_YEAR = 'year';
    const PARAM_MONTH = 'month';
    const PARAM_DATE_START = 'date_start';
    const PARAM_DATE_END = 'date_end';

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        $this->addSourceConstraint(new Type(['type' => 'digit']), self::PARAM_YEAR);
        $this->addSourceConstraint(new Range(['min' => 1970]), self::PARAM_YEAR);

        $this->addSourceConstraint(new Type(['type' => 'digit']), self::PARAM_MONTH);
        $this->addSourceConstraint(new Range(['min' => 1, 'max' => 12]), self::PARAM_MONTH);

        $this->addSourceConstraint(new Type(['type' => 'digit']), self::PARAM_DATE_START);
        $this->addSourceConstraint(new Range(['min' => 1]), self::PARAM_DATE_START);

        $this->addSourceConstraint(new Type(['type' => 'digit']), self::PARAM_DATE_END);
        $this->addSourceConstraint(new Range(['min' => 1]), self::PARAM_DATE_END);
    }
}
