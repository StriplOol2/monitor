<?php

namespace MonitorBundle\Client;

interface ClientInterface
{
    /**
     * @param $url
     * @return mixed
     */
    public function getContent($url);
}
