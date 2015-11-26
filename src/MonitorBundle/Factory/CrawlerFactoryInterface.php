<?php

namespace MonitorBundle\Factory;

use MonitorBundle\Adapter\CrawlerAdapterInterface;

interface CrawlerFactoryInterface
{
    /**
     * @return CrawlerAdapterInterface
     */
    public function create();

    /**
     * @param string $type
     * @return bool
     */
    public function valid($type);
}
