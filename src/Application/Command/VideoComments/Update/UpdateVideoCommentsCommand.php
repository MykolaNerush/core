<?php

declare(strict_types=1);

namespace App\Application\Command\VideoComments\Update;

use Ramsey\Uuid\UuidInterface;

readonly class UpdateVideoCommentsCommand
{
    public function __construct(
        public UuidInterface $currentUuid,
        public ?string       $comment,
    )
    {
    }
}
