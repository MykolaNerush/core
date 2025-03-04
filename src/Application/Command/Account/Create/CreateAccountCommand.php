<?php

declare(strict_types=1);

namespace App\Application\Command\Account\Create;

use App\Domain\Core\User\Entity\User;

readonly class CreateAccountCommand
{
    public function __construct(
        public User $user,
        public string $accountName,
    )
    {
    }
}
