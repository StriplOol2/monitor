<?php

namespace VLru\ApiBundle\Configuration\Params;

use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use VLru\ApiBundle\Request\PaginationParam;
use VLru\ApiBundle\Request\Params\BaseParam;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Pagination extends BaseParam
{
    const PARAM_PAGE = 'page';
    const PARAM_PAGE_SIZE = 'page_size';
    const OPTION_DEFAULT_PAGE_SIZE = 'default_ps';

    /** @var int */
    public $defaultPageSize = PaginationParam::DEFAULT_PAGE_SIZE;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        $this->addSourceConstraint(new Type(['type' => 'digit']), self::PARAM_PAGE);
        $this->addSourceConstraint(new Range(['min' => 1]), self::PARAM_PAGE);

        $this->addSourceConstraint(new Type(['type' => 'digit']), self::PARAM_PAGE_SIZE);
        $this->addSourceConstraint(new Range(['min' => 1, 'max' => 1000]), self::PARAM_PAGE_SIZE);

        $this->setTransformRuleOption(self::OPTION_DEFAULT_PAGE_SIZE, $this->defaultPageSize);
    }
}
