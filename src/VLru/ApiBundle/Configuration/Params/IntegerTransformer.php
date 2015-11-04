<?php

namespace VLru\ApiBundle\Configuration\Params;

use VLru\ApiBundle\Request\Params\ParamTransformer;

class IntegerTransformer extends ParamTransformer
{
    /**
     * @param array $sourceData
     * @return mixed
     */
    public function transform(array $sourceData)
    {
        $value = $this->getMappedValue($sourceData);

        if (null === $value) {
            return $this->getOption(Integer::OPTION_DEFAULT);
        }

        return (int) $value;
    }
}
