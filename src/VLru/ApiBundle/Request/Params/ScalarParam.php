<?php

namespace VLru\ApiBundle\Request\Params;

use Symfony\Component\Validator\Constraints\NotNull;

abstract class ScalarParam extends BaseParam
{
    const OPTION_DEFAULT = 'default';

    /** @var mixed */
    public $default = null;

    /** @var bool */
    public $required = false;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
        $this->setTransformRuleOption(self::OPTION_DEFAULT, $this->default);
        if ($this->required) {
            $this->addSourceConstraint(new NotNull());
        } else {
            $this->registerSource();
        }
    }
}
