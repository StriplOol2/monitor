<?php

namespace VLru\ApiBundle\Configuration\Params;

use VLru\ApiBundle\Request\Params\ParamTransformer;
use VLru\ApiBundle\Request\Params\ParamTransformerFactory;
use VLru\ApiBundle\Request\Params\Metadata\TransformRule;

class ScalarArrayTransformer extends ParamTransformer
{
    /** @var ParamTransformerFactory */
    private $transformersFactory;

    /**
     * @param ParamTransformerFactory $transformersFactory
     */
    public function __construct(ParamTransformerFactory $transformersFactory)
    {
        $this->transformersFactory = $transformersFactory;
    }

    /**
     * @param array $sourceData
     * @return mixed
     */
    public function transform(array $sourceData)
    {
        $sourceArray = $this->getMappedValue($sourceData);

        if (null == $sourceArray) {
            return [];
        }

        /** @var TransformRule $itemsRule */
        $itemsRule = $this->getOption(ScalarArray::OPTION_RULE);
        $itemsMappingKey = reset($itemsRule->mapping);
        $itemsTransformer = $this->transformersFactory->getInitializedTransformer($itemsRule);

        $transformedArray = [];
        foreach ($sourceArray as $key => $value) {
            $transformedValue = $itemsTransformer->transform([$itemsMappingKey => $value]);
            $transformedArray[$key] = $transformedValue;
        }

        return $transformedArray;
    }
}
