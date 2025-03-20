<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Account;

use Symfony\Component\Validator\Constraints as Assert;
use App\Domain\Core\Account\Validator\Constraints\CreateAccountRequestConstraint;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[GroupSequence(['CreateAccountRequest', 'Custom'])]
#[CreateAccountRequestConstraint(groups: ['Custom'])]
class CreateAccountRequest
{
    #[Assert\NotBlank(message: 'The accountName field is required.')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The name must be at least {{ limit }} characters long.',
        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
    )]
    public string $accountName;
}
