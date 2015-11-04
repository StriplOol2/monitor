<?php

namespace VLru\ApiBundle\Configuration\Params;

use VLru\ApiBundle\Request\Params\ParamTransformer;

class StringTransformer extends ParamTransformer
{
    /**
     * @param array $sourceData
     * @return mixed
     */
    public function transform(array $sourceData)
    {
        $value = $this->getMappedValue($sourceData);

        return null === $value ? $this->getOption(String::OPTION_DEFAULT) : (string) $value;
    }
}
