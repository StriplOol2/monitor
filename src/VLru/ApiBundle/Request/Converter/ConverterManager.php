<?php

namespace VLru\ApiBundle\Request\Converter;

class ConverterManager
{
    /** @var ConverterInterface[] */
    protected $converters = [];

    /**
     * @param ConverterInterface $converter
     *
     * @return $this
     */
    public function add(ConverterInterface $converter)
    {
        $this->converters[] = $converter;

        return $this;
    }

    /**
     * @param string $class
     * @param mixed  $value
     *
     * @return mixed
     * @throws ConverterException
     * @throws ConverterInvalidConfigurationException
     */
    public function convert($class, $value)
    {
        foreach ($this->converters as $converter) {
            if ($converter->supports($class)) {
                $convertedValue = $converter->apply($class, $value);

                if (!is_a($convertedValue, $class) && !is_subclass_of($convertedValue, $class)) {
                    throw new ConverterException(
                        "Except instance of '{$class}', got '" . gettype($convertedValue) . "'"
                    );
                }

                return $convertedValue;
            }
        }

        throw new ConverterInvalidConfigurationException("Couldn't found converter for class '{$class}'");
    }
}
