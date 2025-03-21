<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Account;

use App\Domain\Core\Account\Validator\Constraints\GetAccountRequestConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[GroupSequence(['GetAccountRequest', 'Custom'])]
#[GetAccountRequestConstraint(groups: ['Custom'])]
class GetAccountRequest
{
    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public string $uuid;
}
