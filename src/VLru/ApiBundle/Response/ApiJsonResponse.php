<?php

namespace VLru\ApiBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiJsonResponse extends JsonResponse
{
    const EXTRA_TOTAL_COUNT = 'TOTAL_COUNT';
    const EXTRA_PAGE_SIZE = 'PAGE_SIZE';
    const EXTRA_PAGE = 'PAGE';

    protected static $extraHeadersMap = [
        self::EXTRA_TOTAL_COUNT => 'X-Total-Count',
        self::EXTRA_PAGE        => 'X-Page',
        self::EXTRA_PAGE_SIZE   => 'X-Page-Size',
    ];

    /**
     * Constructor.
     *
     * @param mixed $data    The response data
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     */
    public function __construct($data = null, $status = Response::HTTP_OK, $headers = [])
    {
        parent::__construct($data, $status, $headers);
    }

    /**
     * @param string $header
     * @param mixed  $value
     * @param bool   $replace
     *
     * @return $this
     */
    public function setExtra($header, $value, $replace = true)
    {
        if (!isset(self::$extraHeadersMap[$header])) {
            throw new \InvalidArgumentException("Unknown extra header type '{$header}'");
        }

        $this->headers->set(self::$extraHeadersMap[$header], $value, $replace);

        return $this;
    }

    /**
     * @param string     $header
     * @param mixed|null $defaultValue
     *
     * @param bool       $first
     *
     * @return mixed
     */
    public function getExtra($header, $defaultValue = null, $first = true)
    {
        if (!isset(self::$extraHeadersMap[$header])) {
            throw new \InvalidArgumentException("Unknown extra header type '{$header}'");
        }

        return $this->headers
            ->get(self::$extraHeadersMap[$header], $defaultValue, $first);
    }
}
