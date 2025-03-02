<?php

declare(strict_types=1);

namespace App\Application\Command\Video\Delete;

use Ramsey\Uuid\UuidInterface;

readonly class DeleteVideoCommand
{
    public function __construct(
        public UuidInterface $uuid,
    )
    {
    }
}
