<?php

declare(strict_types=1);

namespace App\Application\Command\Account\Delete;

use Ramsey\Uuid\UuidInterface;

readonly class DeleteAccountCommand
{
    public function __construct(
        public UuidInterface $uuid,
    )
    {
    }
}
