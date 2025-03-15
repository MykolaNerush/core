<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Auth;

use App\Application\Shared\Validator\UniqueEmail;
use Symfony\Component\Validator\Constraints as Assert;

class SignInUserRequest
{
    #[Assert\NotBlank(message: 'The password field is required.')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The password must be at least {{ limit }} characters long.',
        maxMessage: 'The password cannot be longer than {{ limit }} characters.'
    )]
    public string $password;

    #[Assert\NotBlank(message: 'The email field is required.')]
    #[Assert\Email(message: 'The email format is invalid.')]
    #[UniqueEmail]
    public string $email;
}
