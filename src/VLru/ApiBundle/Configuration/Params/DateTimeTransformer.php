<?php

namespace VLru\ApiBundle\Configuration\Params;

use VLru\ApiBundle\Request\Params\ParamTransformer;

class DateTimeTransformer extends ParamTransformer
{
    /**
     * @param array $sourceData
     * @return mixed
     */
    public function transform(array $sourceData)
    {
        $value = $this->getMappedValue($sourceData);

        if (null === $value) {
            $value = $this->getOption(DateTime::OPTION_DEFAULT);

            if (null === $value) {
                return null;
            }
        }

        return (new \DateTime())->setTimestamp($value);
    }
}
