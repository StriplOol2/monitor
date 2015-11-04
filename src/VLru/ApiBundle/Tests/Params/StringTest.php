<?php

namespace VLru\ApiBundle\Tests\Params;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Choice;
use VLru\ApiBundle\Configuration\Params\String;
use VLru\ApiBundle\Request\Params\ParamsValidationException;

/**
 * @fast
 * @kernel
 * @covers VLru\ApiBundle\Configuration\Params\String
 * @covers VLru\ApiBundle\Configuration\Params\StringTransformer
 */
class StringTest extends BaseParamsTestCase
{
    public function testLengthValidation()
    {
        $request = Request::create('/test?param=testtest');
        $param = new String(['name' => 'param', 'minLength' => 1, 'maxLength' => 12]);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => 'testtest'], $parsedData);
    }

    public function testLengthValidationFail()
    {
        $request = Request::create('/test?param=testtesttesttest');
        $param = new String(['name' => 'param', 'minLength' => 1, 'maxLength' => 12]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testRegexValidation()
    {
        $request = Request::create('/test?param=a124');
        $param = new String(['name' => 'param', 'pattern' => 'a\\d+']);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => 'a124'], $parsedData);
    }

    public function testRegexValidationFail()
    {
        $request = Request::create('/test?param=asfas');
        $param = new String(['name' => 'param', 'pattern' => 'a\\d+']);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testNull()
    {
        $request = Request::create('/test');
        $param = new String(['name' => 'param']);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => null], $parsedData);
    }

    public function testDefault()
    {
        $request = Request::create('/test');
        $param = new String(['name' => 'param', 'default' => 'test']);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => 'test'], $parsedData);
    }

    public function testRequired()
    {
        $request = Request::create('/test');
        $param = new String(['name' => 'param', 'required' => true]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }

    public function testAttributesWithString()
    {
        $request = Request::create('/test');
        $request->attributes->set('param', 'test');
        $param = new String(['name' => 'param']);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => 'test'], $parsedData);
    }

    public function testAttributesWithNotString()
    {
        $request = Request::create('/test');
        $request->attributes->set('param', 123);
        $param = new String(['name' => 'param']);

        $parsedData = $this->parseRequest($request, $param);
        $this->assertSame(['param' => '123'], $parsedData);
    }

    public function testCustomConstraints()
    {
        $request = Request::create('/test?param=asfas');
        $param = new String(['name' => 'param', 'constraints' => [new Choice(['choices' => ['test1', 'test2']])]]);

        $this->setExpectedException(ParamsValidationException::class);
        $this->parseRequest($request, $param);
    }
}
