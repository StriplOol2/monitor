<?php

namespace MonitorBundle\Factory;

use CommonBundle\Helpers\ArrayHelper;
use CommonBundle\Helpers\DateTimeHelper;
use MonitorBundle\Entity\Advert;

class AdvertFactory
{
    /**
     * @param array $bean
     * @return Advert|null
     */
    public function create(array $bean)
    {
        if (!$url = ArrayHelper::value($bean, 'url')) {
            return null;
        }
        $bulletinDate = trim(ArrayHelper::value($bean, 'bulletin_date'));
        $bulletinDate = DateTimeHelper::parseDateTime($bulletinDate);
        $bulletinDate->setTime(0, 0, 0);
        $result = new Advert();
        $result->setUrl(trim($url))
            ->setId(ArrayHelper::value($bean, 'id'))
            ->setModel(trim(ArrayHelper::value($bean, 'model')))
            ->setMaker(trim(ArrayHelper::value($bean, 'maker')))
            ->setAdditional(trim(ArrayHelper::value($bean, 'additional')))
            ->setBulletinDate($bulletinDate)
            ->setBulletinId(trim(ArrayHelper::value($bean, 'bulletin_id')))
            ->setCity(trim(ArrayHelper::value($bean, 'city')))
            ->setBody(trim(ArrayHelper::value($bean, 'body')))
            ->setColor(trim(ArrayHelper::value($bean, 'color')))
            ->setEngine(trim(ArrayHelper::value($bean, 'engine')))
            ->setGear(trim(ArrayHelper::value($bean, 'gear')))
            ->setHelm(trim(ArrayHelper::value($bean, 'helm')))
            ->setNew((bool) ArrayHelper::valueInt($bean, 'is_new'))
            ->setPrice(ArrayHelper::valueInt($bean, 'price'))
            ->setMileage(trim(ArrayHelper::value($bean, 'mileage')))
            ->setPower(ArrayHelper::valueInt($bean, 'power'))
            ->setTransmission(trim(ArrayHelper::value($bean, 'transmission')))
            ->setYear(ArrayHelper::valueInt($bean, 'year'));
        return $result;
    }
}
