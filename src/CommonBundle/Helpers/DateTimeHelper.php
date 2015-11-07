<?php

namespace CommonBundle\Helpers;

class DateTimeHelper
{
    /**
     * @param $stringDateTime
     * @return \DateTime|null
     */
    public static function parseDateTime($stringDateTime)
    {
        $knownFormats = array(
            "\d{4}\-\d{2}\-\d{2}\s\d{2}\:\d{2}" => "Y-m-d H:i",
            "\d{4}\-\d{2}\-\d{2}" => "Y-m-d",
            "\d{4}\-\d{2}\-\d{2}\s\d{2}\:\d{2}:\d{2}" => "Y-m-d H:i:s",
            "\d{4}\-\d{2}\-\d{2}\d{2}\:\d{2}" => "Y-m-dH:i",
            "\d{4}\-\d{2}\-\d{2}\d{2}\:\d{2}:\d{2}" => "Y-m-dH:i:s",
            "\d{2}\.\d{2}\.\d{4}" => "d.m.Y",
            "\d{2}\.\d{2}\.\d{4}\s\d{2}\:\d{2}:\d{2}" => "d.m.Y H:i:s",
            "\d{2}\.\d{2}\.\d{4}\s\d{1}\:\d{2}:\d{2}" => "d.m.Y h:i:s",
            "\d{2}\-\d{2}\-\d{4}" => "d-m-Y"
        );
        if ($stringDateTime !== null) {
            foreach ($knownFormats as $formatRegExp => $dateTimeFormatDescription) {
                if (\preg_match("/^" . $formatRegExp . "$/", $stringDateTime)) {
                    return \DateTime::createFromFormat($dateTimeFormatDescription, $stringDateTime);
                }
            }
        }

        return null;
    }
}
