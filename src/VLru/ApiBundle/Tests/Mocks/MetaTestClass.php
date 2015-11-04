<?php

namespace VLru\ApiBundle\Tests\Mocks;

use Symfony\Component\Serializer\Annotation\Groups;
use VLru\ApiBundle\Configuration\Serialization\Groups as ExtendedGroups;

class MetaTestClass
{
    /**
     * @ExtendedGroups({"v1", "v2"}, mapping="test")
     */
    public $test1;

    private $test2;

    private $test3;

    public function __construct()
    {
        $this->test1 = 'test1';
        $this->test2 = 'test2';
        $this->test3 = true;
        $this->test4 = 42;
    }

    /**
     * @ExtendedGroups({"v2"})
     * @ExtendedGroups({"v3"}, mapping="test45")
     */
    public function getTest2()
    {
        return $this->test2;
    }

    public function setTest2($test2)
    {
        $this->test2 = $test2;
    }

    /**
     * @Groups({"v1"})
     */
    public function isTest3()
    {
        return $this->test3;
    }

    public function setTest3($test3)
    {
        $this->test3 = $test3;
    }

    public $test4;
}
