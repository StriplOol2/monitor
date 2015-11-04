<?php

namespace VLru\ApiBundle\Tests\Params;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use VLru\ApiBundle\Request\Converter\ConverterManager;
use VLru\ApiBundle\Request\Params\BaseParam;
use VLru\ApiBundle\Request\Params\Metadata\MetadataBuilder;
use VLru\ApiBundle\Request\Params\ParamTransformerFactory;
use VLru\ApiBundle\Request\Params\RequestParser;

abstract class BaseParamsTestCase extends KernelTestCase
{
    /** @var RequestParser */
    protected $requestParser;

    public function setUp()
    {
        $this::bootKernel();

        $container = $this::$kernel->getContainer();
        $this->requestParser = new RequestParser(
            $container->get('validator'),
            new ParamTransformerFactory($container),
            new ConverterManager()
        );
    }

    protected function parseRequest(Request $request, BaseParam $param)
    {
        $metadata = (new MetadataBuilder())->addParamAnnotation($param)->build();
        return $this->requestParser->parseParameters($request, $metadata);
    }
}
