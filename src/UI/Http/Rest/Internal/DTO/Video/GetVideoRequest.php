<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Video;

use App\Domain\Core\Video\Validator\Constrains\GetVideoRequestConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[GroupSequence(['GetVideoRequest', 'Custom'])]
#[GetVideoRequestConstraint(groups: ['Custom'])]
class GetVideoRequest
{
    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public string $uuid;
}
