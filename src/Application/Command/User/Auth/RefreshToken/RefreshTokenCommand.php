<?php

declare(strict_types=1);

namespace App\Application\Command\User\Auth\RefreshToken;

readonly class RefreshTokenCommand
{
    public function __construct(
        public string $refreshToken,
    )
    {
    }
}
