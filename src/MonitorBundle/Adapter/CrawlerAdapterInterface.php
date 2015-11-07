<?php

namespace MonitorBundle\Adapter;

use MonitorBundle\Entity\Advert;

interface CrawlerAdapterInterface
{
    /**
     * @param string $url
     * @return Advert|null
     */
    public function getAdvert($url);

    /**
     * @param string $url
     * @return string[]
     */
    public function getValidUrlsOnPage($url);

    /**
     * @param string $url
     * @return string[]|bool
     */
    public function getAdvertUrls($url);
}
