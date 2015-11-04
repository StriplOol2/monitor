<?php

namespace VLru\ApiBundle\Tests\Serialization;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\ClassMetadata;
use VLru\ApiBundle\Serialization\ExtendedAttributeMetadata;
use VLru\ApiBundle\Serialization\ExtendedMetadataAnnotationLoader;
use VLru\ApiBundle\Tests\Mocks\MetaTestClass;

/**
 * @group fast
 * @covers VLru\ApiBundle\Serialization\ExtendedMetadataAnnotationLoader
 */
class ExtendedMetadataAnnotationLoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var ExtendedMetadataAnnotationLoader */
    protected $loader;

    public function setUp()
    {
        parent::setUp();
        $reader = new AnnotationReader();
        $this->loader = new ExtendedMetadataAnnotationLoader($reader);
    }

    public function testParsing()
    {
        $meta = new ClassMetadata(MetaTestClass::class);
        $this->loader->loadClassMetadata($meta);

        $attrsMeta = $meta->getAttributesMetadata();

        $this->assertCount(4, $attrsMeta);
        $this->assertAttrMeta($attrsMeta["test1"], ["v1" => "test", "v2" => "test"]);
        $this->assertAttrMeta($attrsMeta["test2"], ["v2" => null, "v3" => "test45"]);
        $this->assertAttrMeta($attrsMeta["test3"], ["v1" => null]);
        $this->assertAttrMeta($attrsMeta["test4"], []);
    }

    private function assertAttrMeta($attrMeta, $expectedGroups)
    {
        $this->assertInstanceOf(ExtendedAttributeMetadata::class, $attrMeta);
        $this->assertEquals($expectedGroups, $attrMeta->groups);
    }
}
