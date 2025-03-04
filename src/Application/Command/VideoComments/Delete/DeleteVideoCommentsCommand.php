<?php

declare(strict_types=1);

namespace App\Application\Command\VideoComments\Delete;

use Ramsey\Uuid\UuidInterface;

readonly class DeleteVideoCommentsCommand
{
    public function __construct(
        public UuidInterface $uuid,
    )
    {
    }
}
