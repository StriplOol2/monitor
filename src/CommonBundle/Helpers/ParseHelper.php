<?php

namespace CommonBundle\Helpers;

class ParseHelper
{
    /**
     * @param string $regexp
     * @param string $html
     * @return array
     */
    public static function geListByRegexp($regexp, $html)
    {
        $matches = [];
        preg_match_all($regexp, $html, $matches);
        return $matches;
    }
    /**
     * @param string $regexp
     * @param string $html
     * @return mixed
     */
    public static function getEntityByRegexp($regexp, $html)
    {
        $matches = array();
        preg_match($regexp, $html, $matches);
        return ArrayHelper::value($matches, 1);
    }
}
