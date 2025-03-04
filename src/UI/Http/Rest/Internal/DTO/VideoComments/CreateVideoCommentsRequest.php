<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\VideoComments;

use Symfony\Component\Validator\Constraints as Assert;

class CreateVideoCommentsRequest
{
    #[Assert\NotBlank(message: 'The comment field is required.')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The comment must be at least {{ limit }} characters long.',
        maxMessage: 'The comment cannot be longer than {{ limit }} characters.'
    )]
    public string $comment;

    #[Assert\Uuid(
        message: 'The Video UUID format is invalid.',
        strict: true
    )]
    public ?string $video_uuid = null;
}
