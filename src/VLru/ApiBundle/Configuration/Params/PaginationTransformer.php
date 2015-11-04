<?php

namespace VLru\ApiBundle\Configuration\Params;

use VLru\ApiBundle\Request\PaginationParam;
use VLru\ApiBundle\Request\Params\ParamTransformer;

class PaginationTransformer extends ParamTransformer
{
    /**
     * @param array $sourceData
     * @return mixed
     */
    public function transform(array $sourceData)
    {
        $page = $this->getMappedValue($sourceData, Pagination::PARAM_PAGE);
        if (null === $page) {
            $page = 1;
        }

        $pageSize = $this->getMappedValue($sourceData, Pagination::PARAM_PAGE_SIZE);
        if (null == $pageSize) {
            $pageSize = $this->getOption(Pagination::OPTION_DEFAULT_PAGE_SIZE);
        }

        return new PaginationParam((int) $page, (int) $pageSize);
    }
}
