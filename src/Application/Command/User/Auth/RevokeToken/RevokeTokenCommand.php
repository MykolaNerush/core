<?php

declare(strict_types=1);

namespace App\Application\Command\User\Auth\RevokeToken;

readonly class RevokeTokenCommand
{
    public function __construct(
        public string $token,
    )
    {
    }
}
