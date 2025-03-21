<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Account;

use App\Domain\Core\Account\Validator\Constraints\DeleteAccountRequestConstraint;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\GroupSequence(['DeleteAccountRequest', 'Custom'])]
#[DeleteAccountRequestConstraint(groups: ['Custom'])]
class DeleteAccountRequest
{
    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public string $uuid;
}
