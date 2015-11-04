<?php

namespace VLru\ApiBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonRequestTransformerListener
{
    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ('json' !== $request->getContentType()) {
            return;
        }

        $data = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE !== ($error = json_last_error())) {
            $this->logger->debug('Bad json request', [
                'request_content' => $request->getContent(),
                'json_decode_error' => $error
            ]);
            throw new BadRequestHttpException('Unable to parse json request');
        }

        if ((null !== $data) && (is_array($data))) {
            $request->request->replace($data);
        }
    }
}
