<?php

namespace VLru\ApiBundle\Configuration\Params;

use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use VLru\ApiBundle\Request\Params\BaseParam;
use VLru\ApiBundle\Request\Params\ScalarParam;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class ScalarArray extends BaseParam
{
    const OPTION_RULE = 'items_rule';

    /**
     * @Required
     * @var ScalarParam
     */
    public $items;

    /** @var bool */
    public $required = false;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);

        if ($this->required) {
            $this->addSourceConstraint(new NotBlank());
        }

        $itemsSourceConstraints = $this->items->getSourceConstraints();
        $this->addSourceConstraint(new All(['constraints' => reset($itemsSourceConstraints)]));

        $itemValidationConstraints = $this->items->getValueConstraints();
        $this->addValueConstraint(new All(['constraints' => $itemValidationConstraints]));

        $itemsTransformRule = $this->items->getTransformRule();
        $this->setTransformRuleOption(self::OPTION_RULE, $itemsTransformRule);
    }

    protected function transformBy()
    {
        return 'api.params.transformer.scalar_array';
    }
}
