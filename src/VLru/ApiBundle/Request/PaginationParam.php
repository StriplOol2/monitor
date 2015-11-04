<?php

namespace VLru\ApiBundle\Request;

class PaginationParam
{
    const DEFAULT_PAGE_SIZE = 50;

    /** @var int */
    private $page;

    /** @var int */
    private $pageSize;

    /** @var int */
    private $offset;

    /**
     * @param int $page
     * @param int $pageSize
     */
    public function __construct($page = 1, $pageSize = self::DEFAULT_PAGE_SIZE)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->offset = ($page - 1) * $pageSize;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
