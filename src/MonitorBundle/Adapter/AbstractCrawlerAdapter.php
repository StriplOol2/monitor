<?php

namespace MonitorBundle\Adapter;

use MonitorBundle\Client\ClientInterface;
use MonitorBundle\Factory\AdvertFactory;

abstract class AbstractCrawlerAdapter implements CrawlerAdapterInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var AdvertFactory
     */
    protected $advertFactory;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->advertFactory = new AdvertFactory();
    }

    /**
     * @param $url
     * @return array
     */
    public function getValidUrlsOnPage($url)
    {
        $html = $this->client->getContent($url);
        return $this->parseValidUrls($html);
    }

    /**
     * @param string $url
     * @return array|bool
     */
    public function getAdvertUrls($url)
    {
        $html = $this->client->getContent($url);
        return $this->parseBulletinUrls($html);
    }

    public function getAdvert($url)
    {
        $html = $this->client->getContent($url);
        $bean = array();

        if ($model = $this->parseModel($html)) {
            $bean['model'] = $model;
        }
        if ($engine = $this->parseEngine($html)) {
            $bean['engine'] = $engine;
        }
        if ($power = $this->parsePower($html)) {
            $bean['power'] = $power;
        }
        if ($transmission = $this->parseTransmission($html)) {
            $bean['transmission'] = $transmission;
        }
        if ($gear = $this->parseGear($html)) {
            $bean['gear'] = $gear;
        }
        if ($mileage = $this->parseMileage($html)) {
            $bean['mileage'] = $mileage;
        }
        if ($helm = $this->parseHelm($html)) {
            $bean['helm'] = $helm;
        }
        if ($additional = $this->parseAdditional($html)) {
            $bean['additional'] = $additional;
        }
        if ($city = $this->parseCity($html)) {
            $bean['city'] = $city;
        }
        if ($phones = $this->parsePhones($html)) {
            $bean['phones'] = $phones;
        }
        if ($color = $this->parseColor($html)) {
            $bean['color'] = $color;
        }
        if ($body = $this->parseBody($html)) {
            $bean['body'] = $body;
        }
        if ($bulletinId = $this->parseBulletinId($html)) {
            $bean['bulletin_id'] = $bulletinId;
        }
        if ($bulletinDate = $this->parseBulletinDate($html)) {
            $bean['bulletin_date'] = $bulletinDate;
        }
        if ($price = $this->parsePrice($html)) {
            $bean['price'] = $price;
        }
        if ($year = $this->parseYear($html)) {
            $bean['year'] = $year;
        }
        if (!is_null($isNew = $this->parseIsNew($html))) {
            $bean['is_new'] = $isNew;
        }
        if ($maker = $this->parseMaker($html)) {
            $bean['maker'] = $maker;
        }

        if (!empty($bean)) {
            $bean['url'] = $url;
        }

        $advert = $this->advertFactory->create($bean);
        return $advert;
    }

    abstract protected function parseModel($html);

    abstract protected function parseEngine($html);

    abstract protected function parsePower($html);

    abstract protected function parseTransmission($html);

    abstract protected function parseGear($html);

    abstract protected function parseMileage($html);

    abstract protected function parseHelm($html);

    abstract protected function parseAdditional($html);

    abstract protected function parseCity($html);

    abstract protected function parsePhones($html);

    abstract protected function parseColor($html);

    abstract protected function parseBody($html);

    abstract protected function parseBulletinId($html);

    abstract protected function parseBulletinDate($html);

    abstract protected function parsePrice($html);

    abstract protected function parseYear($html);

    abstract protected function parseIsNew($html);

    abstract protected function parseMaker($html);

    /**
     * @param $html
     * @return array
     */
    abstract protected function parseValidUrls($html);

    /**
     * @param $html
     * @return array
     */
    abstract protected function parseBulletinUrls($html);
}
