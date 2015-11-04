<?php

namespace VLru\ApiBundle\EventListener;

use Symfony\Component\Validator\ConstraintViolation;
use VLru\ApiBundle\Request\Form\FormValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use VLru\ApiBundle\Request\Params\ParamsValidationException;

class ApiExceptionListener
{
    /** @var LoggerInterface */
    private $log;

    /**
     * @param LoggerInterface $log
     */
    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!in_array('application/json', $event->getRequest()->getAcceptableContentTypes())) {
            return;
        }

        $exception = $event->getException();
        if (!($exception instanceof HttpException)) {
            $this->log->error($exception->getMessage(), ['exception' => $exception]);
            $response = new JsonResponse(
                ['message' => 'Unexpected error'],
                500
            );

        } elseif ($exception instanceof ParamsValidationException) {
            $errors = [];
            foreach ($exception->getViolations() as $violation) {
                /** @var ConstraintViolationInterface $violation */
                $errors[$violation->getPropertyPath() ?: ''][] = [
                    'message' => $violation->getMessage(),
                    'invalid_value' => $violation->getInvalidValue()
                ];
            }
            $response = new JsonResponse(
                ['message' => $exception->getMessage(), 'errors' => $errors],
                $exception->getStatusCode()
            );

        } elseif ($exception instanceof FormValidationException) {
            $errors = [];
            foreach ($exception->getIterator() as $error) {
                $errorCause = $error->getCause();

                if (null !== $errorCause && $errorCause instanceof ConstraintViolation) {
                    $errors[$errorCause->getPropertyPath() ?: ''][] = [
                        'message' => $errorCause->getMessage(),
                        'invalid_value' => $errorCause->getInvalidValue()
                    ];
                } else {
                    $errors[''][] = [
                        'message' => $error->getMessage()
                    ];
                }
            }
            $response = new JsonResponse(
                ['message' => $exception->getMessage(), 'errors' => $errors],
                $exception->getStatusCode()
            );
        } else {
            $response = new JsonResponse(
                ['message' => $exception->getMessage()],
                $exception->getStatusCode()
            );
        }

        $event->setResponse($response);
    }
}
