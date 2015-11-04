<?php

namespace VLru\ApiBundle\Request\Params;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ParamsValidationException extends BadRequestHttpException
{
    /** @var ConstraintViolationListInterface */
    protected $violations;

    /**
     * @param ConstraintViolationListInterface $violations
     */
    public function __construct(ConstraintViolationListInterface $violations)
    {
        parent::__construct('Request parameters validation error');
        $this->violations = $violations;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations()
    {
        return $this->violations;
    }
}
