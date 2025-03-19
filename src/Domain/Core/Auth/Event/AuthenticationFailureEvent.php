<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Event;

use Symfony\Contracts\EventDispatcher\Event;

class AuthenticationFailureEvent extends Event
{
    public function __construct(
        private readonly string $email,
        private readonly string $ip,
        private readonly string $errorMessage
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}