<?php

namespace VLru\ApiBundle\Tests\Params;

use Symfony\Component\HttpFoundation\Request;
use VLru\ApiBundle\Configuration\Params\Boolean;
use VLru\ApiBundle\Request\Params\ParamsValidationException;

/**
 * @fast
 * @kernel
 * @covers VLru\ApiBundle\Configuration\Params\Boolean
 * @covers VLru\ApiBundle\Configuration\Params\BooleanTransformer
 */
class BooleanTest extends BaseParamsTestCase
{
    public function parsingData()
    {
        return [
            'null' => ['/test', null],
            'empty' => ['/test?param', true],
            'blank' => ['/test?param=', true],
            '1' => ['/test?param=1', true],
            'true' =>['/test?param=true', true],
            'false' => ['/test?param=false', false],
            '0' => ['/test?param=0', false],
            'random' => ['/test?param=asdasdasd', true],
        ];
    }

    /**
     * @dataProvider parsingData
     * @param $url
     * @param $value
     */
    public function testParsing($url, $value)
    {
        $request = Request::create($url);

        $parsedData = $this->parseRequest($request, new Boolean(['name' => 'param']));
        $this->assertSame(['param' => $value], $parsedData);
    }

    public function testAttributesWithBool()
    {
        $request = Request::create('/test');
        $request->attributes->set('param', true);

        $parsedData = $this->parseRequest($request, new Boolean(['name' => 'param']));
        $this->assertSame(['param' => true], $parsedData);
    }

    public function testAttributesWithNotBool()
    {
        $request = Request::create('/test');
        $request->attributes->set('param', 123);

        $parsedData = $this->parseRequest($request, new Boolean(['name' => 'param']));
        $this->assertSame(['param' => true], $parsedData);
    }

    public function testDefault()
    {
        $request = Request::create('/test');
        $param = new Boolean(['name' => 'param', 'default' => true]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => true], $parsedData);
    }

    public function testRequired()
    {
        $request = Request::create('/test');
        $param = new Boolean(['name' => 'param', 'required' => true]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }
}
