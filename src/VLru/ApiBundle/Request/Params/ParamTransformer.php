<?php

namespace VLru\ApiBundle\Request\Params;

use VLru\ApiBundle\Request\Params\Metadata\TransformRule;

abstract class ParamTransformer
{
    /** @var TransformRule */
    private $rule;

    /**
     * @param TransformRule $rule
     */
    public function initialize(TransformRule $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @param array $sourceData
     * @return mixed
     */
    abstract public function transform(array $sourceData);

    /**
     * @param array $sourceData
     * @param string $valueKey
     * @return mixed
     */
    protected function getMappedValue(array $sourceData, $valueKey = null)
    {
        if (null === $valueKey) {
            $key = reset($this->rule->mapping);
        } else {
            $key = array_search($valueKey, $this->rule->mapping);

            if (false === $key) {
                return null;
            }
        }

        return self::value($sourceData, $key);
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    protected function getOption($key, $defaultValue = null)
    {
        return self::value($this->rule->options, $key, $defaultValue);
    }

    /**
     * @param array $array
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    protected static function value(array $array, $key, $defaultValue = null)
    {
        return array_key_exists($key, $array) ? $array[$key] : $defaultValue;
    }
}
