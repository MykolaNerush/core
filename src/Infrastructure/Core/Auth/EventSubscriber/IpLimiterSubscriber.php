<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Auth\EventSubscriber;

use App\Domain\Core\Auth\Event\AuthenticationFailureEvent;
use App\Domain\Core\Auth\Event\AuthenticationSuccessEvent;
use App\Domain\Core\Auth\Service\AuthenticationLoggerInterface;
use App\Domain\Core\Auth\Service\IpLimiterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;

readonly class IpLimiterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private IpLimiterInterface            $ipLimiter,
        private AuthenticationLoggerInterface $authLogger,
    )
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        // Перевіряємо тільки auth endpoints
        if (!$this->isAuthEndpoint($request)) {
            return;
        }

        $ip = $request->getClientIp() ?? 'unknown';

        if (!$this->ipLimiter->isAllowed($ip)) {
            $event->setResponse(new JsonResponse(
                ['error' => 'Too many failed attempts. Please try again later.'],
                Response::HTTP_TOO_MANY_REQUESTS
            ));
        }
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $ip = $event->getIp();
        if (!$this->ipLimiter->isWhitelisted($ip)) {
            $this->ipLimiter->increment($ip);
            $this->authLogger->logFailure(
                $event->getEmail(),
                $ip,
                $event->getErrorMessage()
            );
        }
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $ip = $event->getIp();
        $this->ipLimiter->reset($ip);
        $this->authLogger->logSuccess(
            $event->getUserUuid(),
            $ip
        );
    }

    private function isAuthEndpoint(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), '/api/v1/internal/signin');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['onKernelRequest', 9],
            AuthenticationSuccessEvent::class => 'onAuthenticationSuccess',
            AuthenticationFailureEvent::class => 'onAuthenticationFailure',
        ];
    }
}