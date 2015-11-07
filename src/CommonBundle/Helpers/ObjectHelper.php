<?php

namespace CommonBundle\Helpers;

class ObjectHelper
{
    /**
     * @param object $object
     * @param string $property
     * @param null|mixed $defaultValue
     * @return null|mixed
     */
    public static function value($object, $property, $defaultValue = null)
    {
        return property_exists($object, $property) ? $object->$property : $defaultValue;
    }

    /**
     * @param object $object
     * @param string $property
     * @param |null $defaultValue
     * @return float|null
     */
    public static function valueFloat($object, $property, $defaultValue = null)
    {
        return property_exists($object, $property)
            ? (float)$object->$property
            : (null === $defaultValue ? null: (float) $defaultValue);
    }

    /**
     * @param object $object
     * @param string $property
     * @param null $defaultValue
     * @return int|null
     */
    public static function valueInt($object, $property, $defaultValue = null)
    {
        return property_exists($object, $property)
            ? (int)$object->$property
            : (null === $defaultValue ? null: (int) $defaultValue);
    }

    /**
     * @param object $object
     * @param string $property
     * @param null $defaultValue
     * @return bool|null
     */
    public static function valueBool($object, $property, $defaultValue = null)
    {
        return property_exists($object, $property)
            ? (bool)$object->$property
            : (null === $defaultValue ? null: (bool) $defaultValue);
    }

    /**
     * @param object $object
     * @param string $property
     * @param \DateTime|null $defaultValue
     * @return \DateTime|null
     */
    public static function valueDate($object, $property, \DateTime $defaultValue = null)
    {
        return self::valueDateTimeFromFormat($object, $property, "Y-m-d", $defaultValue);
    }

    /**
     * @param object $object
     * @param string $property
     * @param string $format
     * @param \DateTime|null $defaultValue
     * @return \DateTime|null
     */
    public static function valueDateTimeFromFormat($object, $property, $format, \DateTime $defaultValue = null)
    {
        return property_exists($object, $property)
            ? \DateTime::createFromFormat($format, $object->$property)
            : $defaultValue;
    }

    /**
     * @param object $object
     * @param string $property
     * @param \DateTime|null $defaultValue
     * @return \DateTime|null
     */
    public static function valueDateTime($object, $property, \DateTime $defaultValue = null)
    {
        return self::valueDateTimeFromFormat($object, $property, "Y-m-d H:i:s", $defaultValue);
    }
}
