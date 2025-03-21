<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\User;

use App\Domain\Core\User\Validator\Constraints\DeleteUserRequestConstraint;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\GroupSequence(['DeleteUserRequest', 'Custom'])]
#[DeleteUserRequestConstraint(groups: ['Custom'])]
class DeleteUserRequest
{
    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public string $uuid;
}
