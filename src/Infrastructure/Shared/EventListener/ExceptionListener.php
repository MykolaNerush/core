<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Psr\Log\LoggerInterface;

readonly class ExceptionListener
{

    public function __construct(private LoggerInterface $logger)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        while ($exception->getPrevious()) {
            $exception = $exception->getPrevious();
        }

        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;

        $response = new JsonResponse([
            'status' => 'error',
            'messages' => [$exception->getMessage()],
            'code' => $statusCode,
        ], $statusCode);

        $this->logger->error('Exception occurred: ' . $exception->getMessage(), [
            'message' => $exception->getMessage(),
            'code' => $statusCode,
            'trace' => $exception->getTraceAsString()
        ]);

        $event->setResponse($response);
    }

}