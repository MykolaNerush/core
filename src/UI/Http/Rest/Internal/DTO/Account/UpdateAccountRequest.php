<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Account;

use App\Domain\Core\Account\Validator\Constraints\AccountUpdateRequestConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[GroupSequence(['UpdateAccountRequest', 'Custom'])]
#[AccountUpdateRequestConstraint(groups: ['Custom'])]
class UpdateAccountRequest
{
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The name must be at least {{ limit }} characters long.',
        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
    )]
    public ?string $accountName;

    public string $uuid;
}
