<?php

namespace MonitorBundle\Strategy;

use MonitorBundle\Factory\SearchFactoryInterface;

class SearchStrategy
{
    /**
     * @var SearchFactoryInterface[]
     */
    protected $factories = [];

    public function addFactory(SearchFactoryInterface $factory)
    {
        $this->factories[] = $factory;
    }

    /**
     * @param string $type
     * @return SearchFactoryInterface
     */
    public function getFactory($type)
    {
        foreach ($this->factories as $factory) {
            if ($factory->validate($type)) {
                return $factory;
            }
        }
    }

    /**
     * @return \MonitorBundle\Factory\SearchFactoryInterface[]
     */
    public function getFactories()
    {
        return $this->factories;
    }
}
