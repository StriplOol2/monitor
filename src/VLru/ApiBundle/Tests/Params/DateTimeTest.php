<?php

namespace VLru\ApiBundle\Tests\Params;

use Symfony\Component\HttpFoundation\Request;
use VLru\ApiBundle\Configuration\Params\DateTime;
use VLru\ApiBundle\Request\Params\ParamsValidationException;

/**
 * @fast
 * @kernel
 * @covers VLru\ApiBundle\Configuration\Params\DateTime
 * @covers VLru\ApiBundle\Configuration\Params\DateTimeTransformer
 */
class DateTimeTest extends BaseParamsTestCase
{
    public function testParsing()
    {
        $request = Request::create('/test?param='.strtotime('10-10-2000 10:30:00'));
        $param = new DateTime(['name' => 'param']);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertEquals(['param' => new \DateTime('10-10-2000 10:30:00')], $parsedData);
    }

    public function testBadFormat()
    {
        $request = Request::create('/test?param=10-10-2000');
        $param = new DateTime(['name' => 'param']);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testNull()
    {
        $request = Request::create('/test');
        $param = new DateTime(['name' => 'param']);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertEquals(['param' => null], $parsedData);
    }

    public function testDefault()
    {
        $request = Request::create('/test');
        $param = new DateTime(['name' => 'param', 'default' => strtotime('10-10-2000 10:30:00')]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertEquals(['param' => new \DateTime('10-10-2000 10:30:00')], $parsedData);
    }

    public function testRequired()
    {
        $request = Request::create('/test');
        $param = new DateTime(['name' => 'param', 'required' => true]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }
}
