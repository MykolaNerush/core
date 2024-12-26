<?php

declare(strict_types=1);

namespace App\Application\Command\User\Delete;

use Ramsey\Uuid\UuidInterface;

readonly class DeleteUserCommand
{
    public function __construct(
        public UuidInterface $uuid,
    )
    {
    }
}
