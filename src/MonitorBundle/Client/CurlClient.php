<?php

namespace MonitorBundle\Client;

use MonitorBundle\Exception\CurlErrorException;
use Psr\Log\LoggerInterface;

class CurlClient implements ClientInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * CurlClient constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $url
     * @return string
     * @throws CurlErrorException
     */
    public function getContent($url)
    {
        $proxyUrl = 'http://dromsoft.ru/proxy.php?url=' . $url;
        $this->logger->info('Generated proxy url', ['proxy_url' => $proxyUrl]);
        $init = curl_init($proxyUrl);
        curl_setopt($init, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $init,
            CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'
        );
        curl_setopt($init, CURLOPT_ENCODING, "");
        curl_setopt($init, CURLOPT_TIMEOUT, 15);

        $html = curl_exec($init);

        if (curl_error($init)) {
            $this->logger->error('Error on curl', ['url' => $url, 'proxy_url' => $proxyUrl]);
            curl_close($init);
            throw new CurlErrorException();
        }

        if (!is_string($html)) {
            curl_close($init);
            throw new CurlErrorException();
        }

        curl_close($init);

        $html = iconv("windows-1251", "utf-8", $html);
        return $html;
    }
}
