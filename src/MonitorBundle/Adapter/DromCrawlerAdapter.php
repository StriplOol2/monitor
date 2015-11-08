<?php

namespace MonitorBundle\Adapter;

use CommonBundle\Helpers\ParseHelper;

class DromCrawlerAdapter extends AbstractCrawlerAdapter
{
    const PATTERN_PART_URL_DROM = '/drom\.ru/';

    protected function parseModel($html)
    {
        return preg_replace('/\+/', ' ', $this->getEntityByRegexp("/\"model\":\"(.+?)\"/ms", $html));
    }

    protected function parseEngine($html)
    {
        return $this->getEntityByRegexp("/(?s)Двигатель:.+?span>\s*(.+?)\s*</ms", $html);
    }

    protected function parsePower($html)
    {
        return $this->getEntityByRegexp("/power=(\d+)/ms", $html);
    }

    protected function parseTransmission($html)
    {
        return $this->getEntityByRegexp("/(?s)Трансмиссия:.+?span>\s*(.+?)\s*</ms", $html);
    }

    protected function parseGear($html)
    {
        return $this->getEntityByRegexp("/(?s)Привод:.+?span>\s*(.+?)\s*</ms", $html);
    }

    protected function parseMileage($html)
    {
        return $this->getEntityByRegexp("/(?s)Пробег по России.+?span>\s*(.+?)\s*</ms", $html);
    }

    protected function parseHelm($html)
    {
        return $this->getEntityByRegexp("/(?s)Руль:.+?span>\s*(.+?)\s*</ms", $html);
    }

    protected function parseAdditional($html)
    {
        return $this->getEntityByRegexp("/(?s)Дополнительно:.+?span>\s*(.+?)\s*\<\/p\>/ms", $html);
    }

    protected function parseCity($html)
    {
        return iconv('cp1251', 'utf-8', urldecode($this->getEntityByRegexp("/\"city\":\"(.+?)\"/ms", $html)));
    }

    protected function parseBody($html)
    {
        return $this->getEntityByRegexp("/(?s)Тип кузова:.+?span>\s*(.+?)\s*</ms", $html);
    }

    protected function parsePhones($html)
    {
        return [];
    }

    protected function parseColor($html)
    {
        return $this->getEntityByRegexp("/(?s)Цвет:.+?span>\s*(.+?)\s*</ms", $html);
    }

    protected function parseBulletinId($html)
    {
        return $this->getEntityByRegexp("/(?s)autoNum.+?>Объявление\s+(.+?)\s*от/ms", $html);
    }

    protected function parseBulletinDate($html)
    {
        return $this->getEntityByRegexp("/autoNum.+?>Объявление\s+.+?\s*от\s*(.+?)\s*</ms", $html);
    }

    protected function parsePrice($html)
    {
        return $this->getEntityByRegexp("/\"sum\":\"(\d+)\"/", $html);
    }

    protected function parseYear($html)
    {
        return $this->getEntityByRegexp("/\"year\":\"(\d+)\"/", $html);
    }

    protected function parseIsNew($html)
    {
        return $this->getEntityByRegexp("/\"is_new\":\"(\d+)\"/", $html);
    }

    protected function parseMaker($html)
    {
        return preg_replace('/\+/', ' ', $this->getEntityByRegexp("/\"maker\":\"(.+?)\"/ms", $html));
    }

    /**
     * @param $html
     * @return array
     */
    protected function parseValidUrls($html)
    {
        return $this->geListByRegexp('/drom\.ru/', $html);
    }

    /**
     * @param string $html
     * @return array
     */
    protected function parseBulletinUrls($html)
    {
        $currentDateTime = new \DateTime();
        $urls = [];
        $advertsHtmlList = $this->geListByRegexp(@"/<tr.+?data-bull-id.+?<\/span>.*?<\/td>.*?<\/tr>/ms", $html);
        foreach ($advertsHtmlList[0] as $advertHtml) {
            $isUpped = !empty($this->getEntityByRegexp(@"/Поднято.+?(наверх)/i", $advertHtml));
            $test= $this->getEntityByRegexp(@"/{$currentDateTime->format('(d-m)')}/i", $advertHtml);
            $actualDate = !empty($test);

            if ($isUpped || $actualDate) {
                $urls[] =  $this->getEntityByRegexp('/href.*?=.*?\"(.+?drom\.ru.+?\.html)/', $advertHtml);
            }
        }
        return $urls;
    }

    /**
     * @param string $regexp
     * @param string $html
     * @return array
     */
    private function geListByRegexp($regexp, $html)
    {
        return ParseHelper::geListByRegexp($regexp, $html);
    }

    /**
     * @param string $regexp
     * @param string $html
     * @return null
     */
    private function getEntityByRegexp($regexp, $html)
    {
        return ParseHelper::getEntityByRegexp($regexp, $html);
    }
}
