<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Event;

use Symfony\Contracts\EventDispatcher\Event;

class AuthenticationSuccessEvent extends Event
{
    public function __construct(
        private readonly string $userUuid,
        private readonly string $ip
    ) {}

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getIp(): string
    {
        return $this->ip;
    }
}