<?php

namespace MonitorBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SearchNotFoundException extends HttpException
{
    /**
     * InvalidTypeException constructor.
     */
    public function __construct()
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, 'search_not_found');
    }
}
