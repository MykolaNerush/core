<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Services\Video;

use App\Application\Shared\Services\Uploader\UploaderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class VideoUploader implements UploaderInterface
{
    public function __construct(
        private ParameterBagInterface $params,
        private Filesystem            $filesystem,
    )
    {
    }

    public function upload(UploadedFile $file): string
    {
        $uploadDir = $this->params->get('upload_video_dir');
        $videoFilename = uniqid('video_', true) . '.' . $file->guessExtension();
        $this->filesystem->mkdir([$uploadDir]);
        $file->move($uploadDir, $videoFilename);
        return $uploadDir . '/' . $videoFilename;
    }
}