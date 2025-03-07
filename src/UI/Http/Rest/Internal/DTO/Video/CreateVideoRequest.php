<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Video;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class CreateVideoRequest
{
    #[Assert\NotBlank(message: 'The title field is required.')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The title must be at least {{ limit }} characters long.',
        maxMessage: 'The title cannot be longer than {{ limit }} characters.'
    )]
    public string $title;

    #[Assert\Length(
        min: 2,
        max: 200,
        minMessage: 'The description must be at least {{ limit }} characters long.',
        maxMessage: 'The description cannot be longer than {{ limit }} characters.'
    )]
    public ?string $description;

    #[Assert\Length(
        min: 2,
        max: 200,
        minMessage: 'The filePath must be at least {{ limit }} characters long.',
        maxMessage: 'The filePath cannot be longer than {{ limit }} characters.'
    )]
    public ?string $filePath;

    #[Assert\Length(
        min: 2,
        max: 200,
        minMessage: 'The thumbnailPath must be at least {{ limit }} characters long.',
        maxMessage: 'The thumbnailPath cannot be longer than {{ limit }} characters.'
    )]
    public ?string $thumbnailPath;

    #[Assert\Positive(message: 'Invalid page parameter')]
    public ?string $duration;

    #[Assert\NotNull(message: 'The file field is required.')]
    #[Assert\File(
        maxSize: '500M',
        mimeTypes: ['video/mp4', 'video/avi', 'video/mpeg', 'video/quicktime']
    )]
    public UploadedFile $file;
}
