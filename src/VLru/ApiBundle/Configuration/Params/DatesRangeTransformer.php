<?php

namespace VLru\ApiBundle\Configuration\Params;

use VLru\ApiBundle\Request\DatesRangeParam;
use VLru\ApiBundle\Request\Params\ParamTransformer;

class DatesRangeTransformer extends ParamTransformer
{
    /**
     * @param array $sourceData
     * @return mixed
     */
    public function transform(array $sourceData)
    {
        $year      = $this->getMappedValue($sourceData, DatesRange::PARAM_YEAR);
        $month     = $this->getMappedValue($sourceData, DatesRange::PARAM_MONTH);
        $dateStart = $this->getMappedValue($sourceData, DatesRange::PARAM_DATE_START);
        $dateEnd   = $this->getMappedValue($sourceData, DatesRange::PARAM_DATE_END);

        if (null !== $dateStart || null !== $dateEnd) {
            return new DatesRangeParam(
                $this->createDateTimeFromTimestamp($dateStart),
                $this->createDateTimeFromTimestamp($dateEnd)
            );
        } elseif (null !== $year) {
            if (null !== $month) {
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
                $start = new \DateTime("{$year}-{$month}-01 00:00:00");
                $end   = clone $start;
                $end
                    ->modify('+1 month')
                    ->modify('-1 days')
                    ->setTime(23, 59, 59);
            } else {
                $start = new \DateTime("{$year}-01-01 00:00:00");
                $end = new \DateTime("{$year}-12-31 23:59:59");
            }

            return new DatesRangeParam($start, $end);
        }

        return null;
    }

    /**
     * @param int|null $timestamp
     *
     * @return \DateTime|null
     */
    protected function createDateTimeFromTimestamp($timestamp)
    {
        if (null === $timestamp) {
            return null;
        }

        return (new \DateTime())
            ->setTimestamp($timestamp);
    }
}
