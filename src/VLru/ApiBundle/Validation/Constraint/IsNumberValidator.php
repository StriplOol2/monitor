<?php

namespace VLru\ApiBundle\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsNumberValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsNumber) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\IsNumber');
        }

        if (null === $value) {
            return;
        }

        if (!is_numeric($value)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
