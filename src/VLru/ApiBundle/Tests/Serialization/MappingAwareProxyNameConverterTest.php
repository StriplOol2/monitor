<?php

namespace VLru\ApiBundle\Tests\Serialization;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use VLru\ApiBundle\Serialization\MappingAwareProxyNameConverter;

/**
 * @group fast
 * @covers VLru\ApiBundle\Serialization\MappingAwareProxyNameConverter
 */
class MappingAwareProxyNameConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingNormalize()
    {
        $converter = new MappingAwareProxyNameConverter();
        $converter->setMapping(["test" => "test2"]);

        $this->assertEquals("test2", $converter->normalize("test"));
    }

    public function testNonMappingNormalize()
    {
        $converter = new MappingAwareProxyNameConverter();
        $converter->setMapping(["test" => "test2"]);

        $this->assertEquals("test3", $converter->normalize("test3"));
    }

    public function testInnerMappingNormalize()
    {
        $innerConverter = $this->getMock(NameConverterInterface::class);
        $innerConverter->method("normalize")->with("anotherProp")->willReturn("another_prop");

        $converter = new MappingAwareProxyNameConverter($innerConverter);
        $converter->setMapping(["testProp" => "anotherProp"]);

        $this->assertEquals("another_prop", $converter->normalize("testProp"));
    }

    public function testInnerNonMappingNormalize()
    {
        $innerConverter = $this->getMock(NameConverterInterface::class);
        $innerConverter->method("normalize")->with("anotherProp2")->willReturn("another_prop2");

        $converter = new MappingAwareProxyNameConverter($innerConverter);
        $converter->setMapping(["testProp" => "anotherProp"]);

        $this->assertEquals("another_prop2", $converter->normalize("anotherProp2"));
    }

    public function testMappingDenormalize()
    {
        $converter = new MappingAwareProxyNameConverter();
        $converter->setMapping(["test" => "test2"]);

        $this->assertEquals("test", $converter->denormalize("test2"));
    }

    public function testNonMappingDenormalize()
    {
        $converter = new MappingAwareProxyNameConverter();
        $converter->setMapping(["test" => "test2"]);

        $this->assertEquals("test3", $converter->denormalize("test3"));
    }

    public function testInnerMappingDenormalize()
    {
        $innerConverter = $this->getMock(NameConverterInterface::class);
        $innerConverter->method("denormalize")->with("another_prop")->willReturn("anotherProp");

        $converter = new MappingAwareProxyNameConverter($innerConverter);
        $converter->setMapping(["testProp" => "anotherProp"]);

        $this->assertEquals("testProp", $converter->denormalize("another_prop"));
    }

    public function testInnerNonMappingDenormalize()
    {
        $innerConverter = $this->getMock(NameConverterInterface::class);
        $innerConverter->method("denormalize")->with("another_prop2")->willReturn("anotherProp2");

        $converter = new MappingAwareProxyNameConverter($innerConverter);
        $converter->setMapping(["testProp" => "anotherProp"]);

        $this->assertEquals("anotherProp2", $converter->denormalize("another_prop2"));
    }
}
