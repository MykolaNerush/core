<?php

declare(strict_types=1);

namespace App\Application\Command\Account\Update;

use Ramsey\Uuid\UuidInterface;

readonly class UpdateAccountCommand
{
    public function __construct(
        public UuidInterface $currentUuid,
        public ?string       $accountName,
    )
    {
    }
}
