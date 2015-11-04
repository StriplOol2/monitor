<?php

namespace VLru\ApiBundle\AnnotationLoader;

use Psr\Log\LoggerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class PathVariableExpressionFunctionProvider implements ExpressionFunctionProviderInterface
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
     * @return ExpressionFunction[] An array of Function instances
     */
    public function getFunctions()
    {
        return [
            new ExpressionFunction(
                'pathVariable',
                function ($name) {
                    return '$matches['.$name.']';
                },
                function ($args, $name) {
                    $this->logger->error(
                        'Somebody called pathVariable expression function evaluator',
                        [
                            'exception' => new \Exception('For stacktrace logging'),
                            '$args' => $args,
                            '$name' => $name
                        ]
                    );
                    return false;
                }
            )
        ];
    }
}
