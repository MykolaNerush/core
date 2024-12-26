<?php

declare(strict_types=1);

namespace App\Application\Command\User\Update;

use Ramsey\Uuid\UuidInterface;

readonly class UpdateUserCommand
{
    public function __construct(
        public UuidInterface $currentUuid,
        public ?string       $name,
        public ?string       $email,
        public ?string       $password
    )
    {
    }
}
