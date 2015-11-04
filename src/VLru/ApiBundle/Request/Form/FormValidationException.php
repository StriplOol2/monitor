<?php

namespace VLru\ApiBundle\Request\Form;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FormValidationException extends BadRequestHttpException
{
    /** @var FormErrorIterator */
    protected $iterator;

    /**
     * @param FormErrorIterator $iterator
     */
    public function __construct(FormErrorIterator $iterator)
    {
        parent::__construct('Form validation error');
        $this->iterator = $iterator;
    }

    /**
     * @return FormErrorIterator
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}
