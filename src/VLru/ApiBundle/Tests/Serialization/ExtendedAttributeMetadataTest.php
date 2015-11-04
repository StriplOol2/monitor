<?php

namespace VLru\ApiBundle\Tests\Serialization;

use Symfony\Component\Serializer\Mapping\AttributeMetadata;
use VLru\ApiBundle\Serialization\ExtendedAttributeMetadata;

/**
 * @group fast
 * @covers VLru\ApiBundle\Serialization\ExtendedAttributeMetadata
 */
class ExtendedAttributeMetadataTest extends \PHPUnit_Framework_TestCase
{
    /** @var ExtendedAttributeMetadata */
    protected $meta;

    protected function setUp()
    {
        parent::setUp();
        $this->meta = new ExtendedAttributeMetadata('test');
    }

    public function testGetGroups()
    {
        $this->meta->addGroup('test1');
        $this->meta->addGroup('test2', 'test');
        $this->meta->addGroup('test3', 'test5');

        $this->assertEquals(['test1', 'test2', 'test3'], $this->meta->getGroups());
    }

    public function testAddGroupSame()
    {
        $this->meta->addGroup('test1', 'ararar');
        $this->meta->addGroup('test2', 'test');
        $this->meta->addGroup('test1', 'test5');

        $this->assertEquals(['test1', 'test2'], $this->meta->getGroups());
        $this->assertEquals('ararar', $this->meta->getMapping(['test1']));
    }

    public function testMergeExtended()
    {
        $anotherMeta = new ExtendedAttributeMetadata('test');
        $anotherMeta->addGroup('test1');
        $anotherMeta->addGroup('test2', 'test2');

        $this->meta->addGroup('test0', 'test2');
        $this->meta->addGroup('test1', 'ararar');
        $this->meta->merge($anotherMeta);

        $this->assertEquals(['test0', 'test1', 'test2'], $this->meta->getGroups());
        $this->assertEquals('ararar', $this->meta->getMapping(['test1']));
        $this->assertEquals('test2', $this->meta->getMapping(['test2']));
    }

    public function testMergeSimple()
    {
        $anotherMeta = new AttributeMetadata('test');
        $anotherMeta->addGroup('test1');
        $anotherMeta->addGroup('test2');

        $this->meta->addGroup('test0', 'test2');
        $this->meta->addGroup('test1', 'ararar');
        $this->meta->merge($anotherMeta);

        $this->assertEquals(['test0', 'test1', 'test2'], $this->meta->getGroups());
        $this->assertEquals('ararar', $this->meta->getMapping(['test1']));
        $this->assertEquals('test', $this->meta->getMapping(['test2']));
    }

    public function testDefaultMapping()
    {
        $this->meta->addGroup('test0');
        $this->assertEquals('test', $this->meta->getMapping(['test0']));
    }

    public function testNotFoundMapping()
    {
        $this->assertEquals('test', $this->meta->getMapping(['test0']));
    }

    public function testFirstMatchMapping()
    {
        $this->meta->addGroup('test1', 'test1');
        $this->meta->addGroup('test0', 'test0');
        $this->assertEquals('test0', $this->meta->getMapping(['test0', 'test1']));
    }
}
