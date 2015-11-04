<?php

namespace VLru\ApiBundle\Request\Params;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use VLru\ApiBundle\Request\Converter\ConverterException;
use VLru\ApiBundle\Request\Converter\ConverterManager;
use VLru\ApiBundle\Request\Params\Metadata\ActionMetadata;

class RequestParser
{
    /** @var ValidatorInterface */
    protected $validator;

    /** @var ParamTransformerFactory */
    protected $transformerFactory;

    /** @var ConverterManager */
    protected $converterManager;

    /** @var CamelCaseToSnakeCaseNameConverter */
    protected $camelCaseConverter;

    /**
     * RequestHandler constructor.
     * @param ValidatorInterface $validator
     * @param ParamTransformerFactory $transformerFactory
     * @param ConverterManager $converterManager
     */
    public function __construct(
        ValidatorInterface $validator,
        ParamTransformerFactory $transformerFactory,
        ConverterManager $converterManager
    ) {
        $this->validator = $validator;
        $this->transformerFactory = $transformerFactory;
        $this->converterManager = $converterManager;
        $this->camelCaseConverter = new CamelCaseToSnakeCaseNameConverter();
    }


    /**
     * @param Request $request
     * @param ActionMetadata $actionMetadata
     * @throws ParamsValidationException
     * @throws ConverterException
     * @return array
     */
    public function parseParameters(Request $request, ActionMetadata $actionMetadata)
    {
        $sourceData = $this->parseSourceData($request, $actionMetadata);
        $parameters = $this->transformSourceData($sourceData, $actionMetadata);

        return $parameters;
    }

    /**
     * @param Request $request
     * @param ActionMetadata $actionMetadata
     * @throws ParamsValidationException
     * @return array
     */
    private function parseSourceData(Request $request, ActionMetadata $actionMetadata)
    {
        $sourceData = [];
        $validationContext = $this->validator->startContext();
        foreach ($actionMetadata->sourceConstraints as $key => $constraints) {
            $sourceData[$key] = $this->getSourceValue($request, $key, in_array($key, $actionMetadata->arraySources));

            $validationContext->atPath($key);
            $validationContext->validate($sourceData[$key], $constraints);
        }

        $errors = $validationContext->getViolations();
        if ($errors->count() > 0) {
            throw new ParamsValidationException($errors);
        }

        return $sourceData;
    }

    /**
     * @param Request $request
     * @param string $key
     * @param bool $isArray
     * @return mixed
     */
    private function getSourceValue(Request $request, $key, $isArray = false)
    {
        $value = $request->attributes->get($key);
        if (null === $value) {
            $value = $request->query->get($this->camelCaseConverter->normalize($key));
        } else {
            $request->attributes->remove($key);
        }

        if ($isArray) {
            if (null === $value) {
                $value = [];
            } elseif (!is_array($value)) {
                $value = explode(',', $value);
            }
        }

        return $value;
    }

    /**
     * @param array $sourceData
     * @param \VLru\ApiBundle\Request\Params\Metadata\ActionMetadata $actionMetadata
     * @throws ParamsValidationException
     * @throws ConverterException
     * @return array
     */
    private function transformSourceData(array $sourceData, ActionMetadata $actionMetadata)
    {
        $parameters = [];
        $validationContext = $this->validator->startContext();
        foreach ($actionMetadata->transformRules as $name => $rule) {
            $transformer = $this->transformerFactory->getInitializedTransformer($rule);
            $value = $transformer->transform($sourceData);

            $validationContext->atPath($name);
            $validationContext->validate($value, $actionMetadata->valueConstraints[$name]);

            if (null !== $value && isset($actionMetadata->valueConverters[$name])) {
                $value = $this->converterManager->convert($actionMetadata->valueConverters[$name], $value);
            }

            $parameters[$name] = $value;
        }

        $errors = $validationContext->getViolations();
        if ($errors->count() > 0) {
            throw new ParamsValidationException($errors);
        }

        return $parameters;
    }
}
