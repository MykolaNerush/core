<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\User;

use App\Domain\Core\User\Validator\Constraints\GetUserRequestConstraint;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\GroupSequence(['GetUserRequest', 'Custom'])]
#[GetUserRequestConstraint(groups: ['Custom'])]
class GetUserRequest
{
    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public string $uuid;
}
