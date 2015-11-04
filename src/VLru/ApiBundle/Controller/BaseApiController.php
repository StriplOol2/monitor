<?php

namespace VLru\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use VLru\ApiBundle\Response\ApiJsonResponse;

abstract class BaseApiController extends Controller
{
    /** @var NormalizerInterface */
    protected $normalizer;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param mixed $data
     * @param string[]|null $groups
     * @return ApiJsonResponse
     */
    protected function createSuccessApiJsonResponse($data, array $groups = null)
    {
        if (null !== $this->normalizer) {
            $data = $this->normalizer->normalize($data, 'json', $groups ? ['groups' => $groups] : []);
        }
        return new ApiJsonResponse($data);
    }

    /**
     * @param string $errorMessage
     * @param int $status
     * @return ApiJsonResponse
     */
    protected function createErrorApiJsonResponse($errorMessage, $status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $data = [
            'message' => $errorMessage,
        ];

        return new ApiJsonResponse($data, $status);
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     * @return string
     */
    protected function convertConstraintViolationListToString(ConstraintViolationListInterface $constraintViolationList)
    {
        /** @var string[] $result */
        $result = [];

        foreach ($constraintViolationList as $constraintViolation) {
            /** @var ConstraintViolationInterface $constraintViolation */
            $result[] = $constraintViolation->getMessage();
        }

        return implode("\n", $result);
    }

    /**
     * @param array $data
     * @return Response
     */
    protected function createLdJsonResponse(array $data)
    {
        $ldJson = '';
        foreach ($data as $key => $value) {
            if ($key > 0) {
                $ldJson .= "\n";
            }
            $ldJson .= json_encode($value);
        }

        return new Response($ldJson, 200, [
            'Content-Type' => 'application/x-ldjson'
        ]);
    }
}
