<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Account;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateAccountRequest
{
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The name must be at least {{ limit }} characters long.',
        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
    )]
    public ?string $name;
}
