<?php

namespace VLru\ApiBundle\Configuration\Params;

use VLru\ApiBundle\Request\Params\ParamTransformer;

class BooleanTransformer extends ParamTransformer
{
    /**
     * @param array $sourceData
     * @return mixed
     */
    public function transform(array $sourceData)
    {
        $value = $this->getMappedValue($sourceData);

        if (null === $value) {
            return $this->getOption(Boolean::OPTION_DEFAULT);
        } elseif (is_bool($value)) {
            return $value;
        } elseif (true === in_array(strtolower($value), ['', 'true', '1'])) {
            return true;
        } elseif (true === in_array(strtolower($value), ['false', '0'])) {
            return false;
        } else {
            return (bool) $value;
        }
    }
}
