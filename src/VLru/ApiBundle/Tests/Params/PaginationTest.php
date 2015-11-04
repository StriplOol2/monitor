<?php

namespace VLru\ApiBundle\Tests\Params;

use Symfony\Component\HttpFoundation\Request;
use VLru\ApiBundle\Configuration\Params\Pagination;
use VLru\ApiBundle\Request\PaginationParam;
use VLru\ApiBundle\Request\Params\ParamsValidationException;

/**
 * @fast
 * @kernel
 * @covers VLru\ApiBundle\Configuration\Params\Pagination
 * @covers VLru\ApiBundle\Configuration\Params\PaginationTransformer
 */
class PaginationTest extends BaseParamsTestCase
{
    public function testParsing()
    {
        $request = Request::create('/test?page=2&page_size=20');
        $param = new Pagination(['name' => 'param']);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertEquals(['param' => new PaginationParam(2, 20)], $parsedData);
    }

    public function testParsingBadPage()
    {
        $request = Request::create('/test?page=2asd');
        $param = new Pagination(['name' => 'param']);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testParsingBadPageSize()
    {
        $request = Request::create('/test?page_size=-23');
        $param = new Pagination(['name' => 'param']);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testDefaultValue()
    {
        $request = Request::create('/test');
        $param = new Pagination(['name' => 'param', 'defaultPageSize' => 10]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertEquals(['param' => new PaginationParam(1, 10)], $parsedData);
    }

    public function testBadAttributesValues()
    {
        $request = Request::create('/test');
        $request->attributes->set('page', true);
        $param = new Pagination(['name' => 'param', 'defaultPageSize' => 10]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testMapping()
    {
        $request = Request::create('/test');
        $request->attributes->set('page?p=1', true);
        $param = new Pagination(['name' => 'param', 'defaultPageSize' => 10, 'mapping' => ['page' => 'p']]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertEquals(['param' => new PaginationParam(1, 10)], $parsedData);
    }
}
