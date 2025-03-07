<?php

declare(strict_types=1);

namespace App\Application\Shared\Services\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderInterface
{
    public function upload(UploadedFile $file): string;
}
