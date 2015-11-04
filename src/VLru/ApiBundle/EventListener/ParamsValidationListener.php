<?php

namespace VLru\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use VLru\ApiBundle\Controller\BaseApiController;
use VLru\ApiBundle\Request\Converter\ConverterException;
use VLru\ApiBundle\Request\Params\Metadata\MetadataFactory;
use VLru\ApiBundle\Request\Params\RequestParser;

class ParamsValidationListener
{
    /** @var \VLru\ApiBundle\Request\Params\Metadata\MetadataFactory */
    protected $metadataFactory;

    /** @var RequestParser */
    protected $requestParser;

    /**
     * @param \VLru\ApiBundle\Request\Params\Metadata\MetadataFactory $metadataFactory
     * @param RequestParser $requestParser
     */
    public function __construct(
        MetadataFactory $metadataFactory,
        RequestParser $requestParser
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->requestParser = $requestParser;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller) || !($controller[0] instanceof BaseApiController)) {
            return;
        }

        $request = $event->getRequest();
        $actionMetadata = $this->metadataFactory->getMetadataFor($controller);

        try {
            $parameters = $this->requestParser->parseParameters($request, $actionMetadata);
        } catch (ConverterException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        foreach ($parameters as $name => $value) {
            if ($request->attributes->has($name) && !is_null($request->attributes->get($name))) {
                $route = $request->attributes->get('_route');
                throw new \InvalidArgumentException(
                    "Parameter '$name' conflicts with path parameters from route '$route'"
                );
            }

            $request->attributes->set($name, $value);
        }

        foreach ($actionMetadata->paramsBags as $name => $class) {
            $paramsBag = new $class($parameters);
            if ($request->attributes->has($name) && null !== $request->attributes->get($name)) {
                $route = $request->attributes->get('_route');
                throw new \InvalidArgumentException(
                    "ParamsBag '$name' conflicts with path parameters from route '$route'"
                );
            }
            $request->attributes->set($name, $paramsBag);
        }
    }
}
