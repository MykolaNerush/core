<?php

declare(strict_types=1);

namespace App\Application\Command\Video\Create;

readonly class CreateVideoCommand
{
    public function __construct(
        public ?string $title,
        public ?string $description,
        public ?string $filePath,
        public ?string $thumbnailPath,
        public int     $duration = 0,
    )
    {
    }
}
