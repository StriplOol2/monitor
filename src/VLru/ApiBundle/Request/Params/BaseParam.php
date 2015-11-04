<?php

namespace VLru\ApiBundle\Request\Params;

use Symfony\Component\Validator\Constraint;
use VLru\ApiBundle\Request\Params\Metadata\TransformRule;

abstract class BaseParam
{
    /** @var string */
    private $name;

    /** @var string[] */
    private $mapping;

    /** @var Constraint[] */
    private $constraints;

    /** @var Constraint[][] */
    private $sourceConstraints = [];

    /** @var array */
    private $ruleOptions = [];

    /**
     * Fully qualified class name for conversion or null if not needed
     *
     * @var string|null
     */
    private $convertTo;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (!isset($options['name'])) {
            if (isset($options['value'])) {
                $this->name = $options['value'];
                unset($options['value']);
            } else {
                $this->name = 0;
            }
        } else {
            $this->name = $options['name'];
            unset($options['name']);
        }

        if (array_key_exists('mapping', $options)) {
            $this->mapping = $options['mapping'];
            unset($options['mapping']);
        } else {
            $this->mapping = [$this->name];
        }

        if (array_key_exists('constraints', $options)) {
            $this->constraints = $options['constraints'];
            unset($options['constraints']);
        } else {
            $this->constraints = [];
        }

        if (array_key_exists('convertTo', $options)) {
            $this->convertTo = $options['convertTo'];
            unset($options['convertTo']);
        } else {
            $this->convertTo = null;
        }

        unset($options['sourceConstraints']);
        unset($options['ruleOptions']);

        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return string
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * @return Constraint[][]
     */
    final public function getSourceConstraints()
    {
        return $this->sourceConstraints;
    }

    /**
     * @return Constraint[]
     */
    final public function getValueConstraints()
    {
        return $this->constraints;
    }

    /**
     * @return TransformRule
     */
    final public function getTransformRule()
    {
        $rule = new TransformRule();
        $rule->transformBy = $this->transformBy();
        $rule->mapping = $this->mapping;
        $rule->options = $this->ruleOptions;

        return $rule;
    }

    /**
     * @return null|string
     */
    final public function getConvertTo()
    {
        return $this->convertTo;
    }

    /**
     * @return string
     */
    protected function transformBy()
    {
        return get_class($this) . 'Transformer';
    }

    /**
     * @param int $sourceKey
     * @return string
     */
    protected function registerSource($sourceKey = 0)
    {
        if (!array_key_exists($sourceKey, $this->mapping)) {
            $this->mapping[$sourceKey] = $sourceKey;
        }

        $mappedKey = $this->mapping[$sourceKey];

        if (!array_key_exists($mappedKey, $this->sourceConstraints)) {
            $this->sourceConstraints[$mappedKey] = [];
        }

        return $mappedKey;
    }

    /**
     * @param Constraint $constraint
     * @param mixed $sourceKey
     */
    protected function addSourceConstraint($constraint, $sourceKey = 0)
    {
        $mappedKey = $this->registerSource($sourceKey);
        $this->sourceConstraints[$mappedKey][] = $constraint;
    }

    /**
     * @param Constraint $constraint
     */
    protected function addValueConstraint(Constraint $constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    protected function setTransformRuleOption($key, $value)
    {
        $this->ruleOptions[$key] = $value;
    }
}
