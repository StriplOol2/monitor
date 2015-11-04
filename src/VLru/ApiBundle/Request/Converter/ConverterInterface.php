<?php

namespace VLru\ApiBundle\Request\Converter;

interface ConverterInterface
{
    /**
     * @param string $class
     *
     * @return bool
     */
    public function supports($class);

    /**
     * @param string $class
     * @param mixed $value
     *
     * @return mixed
     */
    public function apply($class, $value);
}
