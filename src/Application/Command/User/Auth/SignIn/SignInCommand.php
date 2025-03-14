<?php

declare(strict_types=1);

namespace App\Application\Command\User\Auth\SignIn;

readonly class SignInCommand
{
    public function __construct(
        public string $email,
        public string $password
    )
    {
    }
}
