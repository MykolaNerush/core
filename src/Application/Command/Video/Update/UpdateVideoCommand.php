<?php

declare(strict_types=1);

namespace App\Application\Command\Video\Update;

use Ramsey\Uuid\UuidInterface;

readonly class UpdateVideoCommand
{
    public function __construct(
        public UuidInterface $currentUuid,
        public ?string       $title,
        public ?string       $description,
        public ?string       $filePath,
        public ?string       $thumbnailPath,
    )
    {
    }
}
