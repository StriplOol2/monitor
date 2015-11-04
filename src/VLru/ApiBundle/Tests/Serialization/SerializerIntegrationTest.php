<?php

namespace VLru\ApiBundle\Tests\Serialization;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Serializer;
use VLru\ApiBundle\Tests\Mocks\MetaTestClass;

/**
 * @group fast
 * @group kernel
 */
class SerializerIntegrationTest extends KernelTestCase
{
    /** @var Serializer */
    protected $serializer;

    /** @var MetaTestClass */
    protected $obj;

    protected $normalizedObj = [
        'test1' => 'd1',
        'test2' => 'd2',
        'test3' => false,
        'test4' => 24,
        'test' => 'd',
        'test45' => 'd45'
    ];

    public function setUp()
    {
        $this::bootKernel();
        $this->serializer = $this::$kernel->getContainer()->get('serializer');
        $this->obj = new MetaTestClass();
    }

    public function normalizationData()
    {
        return [
            'default' => [null, ['test1' => 'test1', 'test2' => 'test2', 'test3' => true, 'test4' => 42]],
            'v1' => [['v1'], ['test' => 'test1', 'test3' => true]],
            'v2' => [['v2'], ['test' => 'test1', 'test2' => 'test2']],
            'v3' => [['v3'], ['test45' => 'test2']],
            'combined' => [['v2', 'v3'], ['test' => 'test1', 'test2' => 'test2']],
            'r_combined' => [['v3', 'v2'], ['test' => 'test1', 'test45' => 'test2']],
        ];
    }

    /**
     * @dataProvider normalizationData
     * @param array|null $groups
     * @param array $expectedResult
     */
    public function testNormalization($groups, $expectedResult)
    {
        $result = $this->serializer->normalize($this->obj, 'json', $groups ? ['groups' => $groups] : []);
        $this->assertEquals($expectedResult, $result);
    }

    public function denormalizationData()
    {
        return [
            'default' => [null, ['test1' => 'd1', 'test2' => 'd2', 'test3' => false, 'test4' => 24]],
            'v1' => [['v1'], ['test1' => 'd', 'test2' => 'test2', 'test3' => false, 'test4' => 42]],
            'v2' => [['v2'], ['test1' => 'd', 'test2' => 'd2', 'test3' => true, 'test4' => 42]],
            'v3' => [['v3'], ['test1' => 'test1', 'test2' => 'd45', 'test3' => true, 'test4' => 42]],
            'combined' => [['v2', 'v3'], ['test1' => 'd', 'test2' => 'd2', 'test3' => true, 'test4' => 42]],
            'r_combined' => [['v3', 'v2'], ['test1' => 'd', 'test2' => 'd45', 'test3' => true, 'test4' => 42]],
        ];
    }

    /**
     * @dataProvider denormalizationData
     * @param $groups
     * @param $expectedResult
     */
    public function testDeserialized($groups, $expectedResult)
    {
        $result = $this->serializer->denormalize(
            $this->normalizedObj,
            MetaTestClass::class,
            'json',
            $groups ? ['groups' => $groups] : []
        );

        $this->assertEquals($result->test1, $expectedResult['test1']);
        $this->assertEquals($result->getTest2(), $expectedResult['test2']);
        $this->assertEquals($result->isTest3(), $expectedResult['test3']);
        $this->assertEquals($result->test4, $expectedResult['test4']);
    }
}
