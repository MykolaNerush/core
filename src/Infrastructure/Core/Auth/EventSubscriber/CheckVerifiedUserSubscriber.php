<?php

namespace App\Infrastructure\Core\Auth\EventSubscriber;

use App\Domain\Core\Auth\Event\AuthenticationSuccessEvent;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        /* @var User $user */
        $user = $this->userRepository->getByUuid(Uuid::fromBytes($event->getUserUuid()));

        if (!$user->getIsEmailConfirmed()) {
            throw new \Exception('Email not verified');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationSuccessEvent::class => 'onAuthenticationSuccess',
        ];
    }
}