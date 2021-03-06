<?php

namespace MonitorBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserNotFoundException extends HttpException
{
    /**
     * UserNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, 'user_not_found');
    }
}
