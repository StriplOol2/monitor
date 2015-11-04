<?php

namespace VLru\ApiBundle\Tests\Converters;

use VLru\ApiBundle\Request\Converter\ConverterInterface;
use VLru\ApiBundle\Request\Converter\ConverterManager;

class ConverterManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ConverterInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $converter;

    /** @var ConverterManager */
    protected $manager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->converter = $this->getMockBuilder(ConverterInterface::class)
            ->getMock();
        $this->manager = new ConverterManager();
        $this->manager->add($this->converter);

        parent::setUp();
    }

    public function testValueConversion()
    {
        $this->converter
            ->expects($this->once())
            ->method('supports')
            ->with('stdClass')
            ->willReturn(true);

        $expected = new \stdClass();
        $this->converter
            ->expects($this->once())
            ->method('apply')
            ->with('stdClass', 123)
            ->willReturn($expected);

        $actual = $this->manager->convert('stdClass', 123);
        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException \VLru\ApiBundle\Request\Converter\ConverterException
     */
    public function testIncorrectValueConversion()
    {
        $this->converter
            ->expects($this->once())
            ->method('supports')
            ->with('stdClass')
            ->willReturn(true);

        $this->converter
            ->expects($this->once())
            ->method('apply')
            ->with('stdClass', 123);

        $this->manager->convert('stdClass', 123);
    }

    /**
     * @expectedException \VLru\ApiBundle\Request\Converter\ConverterInvalidConfigurationException
     */
    public function testNotFoundConverter()
    {
        $this->converter
            ->expects($this->once())
            ->method('supports')
            ->with('stdClass')
            ->willReturn(false);

        $this->manager->convert('stdClass', 123);
    }
}
