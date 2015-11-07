<?php

namespace MonitorBundle\Strategy;

use MonitorBundle\Exception\InvalidTypeException;
use MonitorBundle\Factory\CrawlerFactoryInterface;

class CrawlerStrategy
{
    /**
     * @var CrawlerFactoryInterface[]
     */
    protected $crawlerFactories = [];

    /**
     * @param CrawlerFactoryInterface $crawlerFactory
     */
    public function add(CrawlerFactoryInterface $crawlerFactory)
    {
        $this->crawlerFactories[] = $crawlerFactory;
    }

    /**
     * @param string $type
     * @return CrawlerFactoryInterface
     */
    public function getFactory($type)
    {
        foreach ($this->crawlerFactories as $crawlerFactory) {
            if ($crawlerFactory->valid($type)) {
                return $crawlerFactory;
            }
        }

        throw new InvalidTypeException;
    }
}
