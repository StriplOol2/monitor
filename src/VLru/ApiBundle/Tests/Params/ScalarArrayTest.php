<?php

namespace VLru\ApiBundle\Tests\Params;

use Symfony\Component\HttpFoundation\Request;
use VLru\ApiBundle\Configuration\Params\Integer;
use VLru\ApiBundle\Configuration\Params\ScalarArray;
use VLru\ApiBundle\Request\Params\ParamsValidationException;

/**
 * @fast
 * @kernel
 * @covers VLru\ApiBundle\Configuration\Params\ScalarArray
 * @covers VLru\ApiBundle\Configuration\Params\ScalarArrayTransformer
 */
class ScalarArrayTest extends BaseParamsTestCase
{
    public function testParsing()
    {
        $request = Request::create('/test?param=1,2,3');
        $param = new ScalarArray(['name' => 'param', 'items' => new Integer([])]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => [1, 2, 3]], $parsedData);
    }

    public function testParsingSingle()
    {
        $request = Request::create('/test?param=1');
        $param = new ScalarArray(['name' => 'param', 'items' => new Integer([])]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => [1]], $parsedData);
    }

    public function testParsingBracesFormat()
    {
        $request = Request::create('/test?param[]=1&param[]=2&param[]=3');
        $param = new ScalarArray(['name' => 'param', 'items' => new Integer([])]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => [1, 2, 3]], $parsedData);
    }

    public function testBadData()
    {
        $request = Request::create('/test?param=1,asdsad,2');
        $param = new ScalarArray(['name' => 'param', 'items' => new Integer([])]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testNull()
    {
        $request = Request::create('/test');
        $param = new ScalarArray(['name' => 'param', 'items' => new Integer([])]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => []], $parsedData);
    }

    public function testRequired()
    {
        $request = Request::create('/test');
        $param = new ScalarArray(['name' => 'param', 'items' => new Integer([]), 'required' => true]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }
}
