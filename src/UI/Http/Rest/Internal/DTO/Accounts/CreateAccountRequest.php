<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Accounts;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAccountRequest
{
    #[Assert\NotBlank(message: 'The name field is required.')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The name must be at least {{ limit }} characters long.',
        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
    )]
    public string $name;
}
