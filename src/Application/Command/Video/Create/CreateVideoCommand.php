<?php

declare(strict_types=1);

namespace App\Application\Command\Video\Create;

use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class CreateVideoCommand
{
    public function __construct(
        public string       $title,
        public ?string      $description,
        public ?string      $thumbnailPath,
        public UploadedFile $videoFile,
        public int          $duration = 0,
    )
    {
    }
}
