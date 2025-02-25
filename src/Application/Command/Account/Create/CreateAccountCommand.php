<?php

declare(strict_types=1);

namespace App\Application\Command\Account\Create;

use Ramsey\Uuid\UuidInterface;

readonly class CreateAccountCommand
{
    public function __construct(
        public UuidInterface $uuid,
        public string        $name,
    )
    {
    }
}
