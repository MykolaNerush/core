<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Video;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateVideoRequest
{
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The title must be at least {{ limit }} characters long.',
        maxMessage: 'The title cannot be longer than {{ limit }} characters.'
    )]
    public ?string $title = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The description must be at least {{ limit }} characters long.',
        maxMessage: 'The description cannot be longer than {{ limit }} characters.'
    )]
    public ?string $description = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The filePath must be at least {{ limit }} characters long.',
        maxMessage: 'The filePath cannot be longer than {{ limit }} characters.'
    )]
    public ?string $filePath = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The thumbnailPath must be at least {{ limit }} characters long.',
        maxMessage: 'The thumbnailPath cannot be longer than {{ limit }} characters.'
    )]
    public ?string $thumbnailPath = null;
}
