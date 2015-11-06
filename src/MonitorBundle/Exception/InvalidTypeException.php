<?php

namespace MonitorBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidTypeException extends HttpException
{
    /**
     * InvalidTypeException constructor.
     */
    public function __construct()
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, 'invalid_type');
    }
}
