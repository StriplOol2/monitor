<?php

namespace CommonBundle\Helpers;

class ArrayHelper
{
    /**
     * @param array $array
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public static function value(array $array, $key, $defaultValue = null)
    {
        return array_key_exists($key, $array) ? $array[$key] : $defaultValue;
    }

    /**
     * @param array $array
     * @param string $key
     * @param int|null $defaultValue
     * @return int|null
     */
    public static function valueInt(array $array, $key, $defaultValue = null)
    {
        return array_key_exists($key, $array)
            ? (int) $array[$key]
            : (null === $defaultValue ? null: (int) $defaultValue);
    }

    /**
     * @param array $array
     * @param string $key
     * @param float|null $defaultValue
     * @return float|null
     */
    public static function valueFloat(array $array, $key, $defaultValue = null)
    {
        return array_key_exists($key, $array)
            ? (float)$array[$key]
            : (null === $defaultValue ? null: (float) $defaultValue);
    }

    /**
     * @param array $array
     * @param string $key
     * @param bool|null $defaultValue
     * @return bool|null
     */
    public static function valueBool(array $array, $key, $defaultValue = null)
    {
        return array_key_exists($key, $array)
            ? (bool)$array[$key]
            : (null === $defaultValue ? null: (bool) $defaultValue);
    }

    /**
     * @param array $array
     * @param string $key
     * @param \DateTime|null $defaultValue
     * @return \DateTime|null
     */
    public static function valueDate(array $array, $key, \DateTime $defaultValue = null)
    {
        return self::valueDateTimeFromFormat($array, $key, "Y-m-d", $defaultValue);
    }

    /**
     * @param array $array
     * @param string $key
     * @param string $format
     * @param \DateTime|null $defaultValue
     * @return \DateTime|null
     */
    public static function valueDateTimeFromFormat(array $array, $key, $format, \DateTime $defaultValue = null)
    {
        return array_key_exists($key, $array)
            ? \DateTime::createFromFormat($format, $array[$key])
            : $defaultValue;
    }

    /**
     * @param array $array
     * @param string $key
     * @param \DateTime|null $defaultValue
     * @return \DateTime|null
     */
    public static function valueDateTime(array $array, $key, \DateTime $defaultValue = null)
    {
        return self::valueDateTimeFromFormat($array, $key, "Y-m-d H:i:s", $defaultValue);
    }

    /**
     * @param array $array
     * @param int $count
     * @return array
     */
    public static function getRandElementsFromArray(array $array, $count)
    {
        $count = \count($array) < $count ? \count($array) : $count;
        $randKeys = \array_rand(\array_keys($array), $count);

        if (\is_null($randKeys)) {
            return array();
        }

        $randKeys = \is_array($randKeys) ? $randKeys : array($randKeys);
        $rand = array();
        foreach ($randKeys as $key) {
            if (isset($array[$key])) {
                $rand[] = $array[$key];
            }
        }

        return $rand;
    }
}
