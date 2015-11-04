<?php

namespace VLru\ApiBundle\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

class IsNumber extends Constraint
{
    public $message = 'Value is not a number';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
