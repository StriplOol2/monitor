<?php

namespace MonitorBundle\Factory;

use MonitorBundle\Client\ClientInterface;
use MonitorBundle\Enum\TypesEnum;
use MonitorBundle\Adapter\CrawlerAdapterInterface;
use MonitorBundle\Adapter\DromCrawlerAdapter;

class DromCrawlerFactory implements CrawlerFactoryInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * DromCrawlerFactory constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return CrawlerAdapterInterface
     */
    public function create()
    {
        return new DromCrawlerAdapter($this->client);
    }

    /**
     * @param string $type
     * @return bool
     */
    public function valid($type)
    {
        return TypesEnum::$DROM_TYPE === $type;
    }
}
