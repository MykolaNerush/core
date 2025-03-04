<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class GetUserRequest
{
    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public string $uuid;
}
