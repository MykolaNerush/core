<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Accounts;

use Symfony\Component\Validator\Constraints as Assert;

class GetAccountRequest
{
    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public string $uuid;
}
