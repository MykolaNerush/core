<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\VideoComments;

use Symfony\Component\Validator\Constraints as Assert;

class GetVideoCommentRequest
{
    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public string $uuid;
}
