<?php

namespace VLru\ApiBundle\Tests\Params;

use Symfony\Component\HttpFoundation\Request;
use VLru\ApiBundle\Configuration\Params\Integer;
use VLru\ApiBundle\Request\Params\ParamsValidationException;

/**
 * @fast
 * @kernel
 * @covers VLru\ApiBundle\Configuration\Params\Integer
 * @covers VLru\ApiBundle\Configuration\Params\IntegerTransformer
 */
class IntegerTest extends BaseParamsTestCase
{
    public function testParsing()
    {
        $request = Request::create('/test?param=123');
        $param = new Integer(['name' => 'param']);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => 123], $parsedData);
    }

    public function testParsingFail()
    {
        $request = Request::create('/test?param=1s23');
        $param = new Integer(['name' => 'param']);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testRangeValidation()
    {
        $request = Request::create('/test?param=10');
        $param = new Integer(['name' => 'param', 'min' => 1, 'max' => 12]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => 10], $parsedData);
    }

    public function testRangeValidationFail()
    {
        $request = Request::create('/test?param=123');
        $param = new Integer(['name' => 'param', 'min' => 1, 'max' => 12]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testNull()
    {
        $request = Request::create('/test');
        $param = new Integer(['name' => 'param', 'min' => 1, 'max' => 12]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => null], $parsedData);
    }

    public function testAttributesWithInt()
    {
        $request = Request::create('/test');
        $request->attributes->set('param', 123);

        $parsedData = $this->parseRequest($request, new Integer(['name' => 'param']));
        $this->assertSame(['param' => 123], $parsedData);
    }

    public function testAttributesWithNotInt()
    {
        $request = Request::create('/test');
        $request->attributes->set('param', true);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, new Integer(['name' => 'param']));
    }

    public function testDefault()
    {
        $request = Request::create('/test');
        $param = new Integer(['name' => 'param', 'default' => 123]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => 123], $parsedData);
    }

    public function testRequired()
    {
        $request = Request::create('/test');
        $param = new Integer(['name' => 'param', 'required' => true]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }
}
