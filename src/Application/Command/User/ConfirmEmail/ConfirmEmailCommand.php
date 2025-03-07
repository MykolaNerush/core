<?php

declare(strict_types=1);

namespace App\Application\Command\User\ConfirmEmail;

readonly class ConfirmEmailCommand
{
    public function __construct(public string $email)
    {
    }
}
