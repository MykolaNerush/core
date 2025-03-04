<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;
use App\Application\Shared\Validator\UniqueEmail;

class UpdateUserRequest
{
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The name must be at least {{ limit }} characters long.',
        maxMessage: 'The name cannot be longer than {{ limit }} characters.'
    )]
    public ?string $name;

    #[Assert\Email(message: 'The email format is invalid.')]
    #[UniqueEmail]
    public ?string $email;

    #[Assert\Length(
        min: 4,
        minMessage: 'The password must be at least {{ limit }} characters long.'
    )]
//!!! for local, uncomment if prod !!!
//    #[Assert\Regex(
//        pattern: '/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+/',
//        message: 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character.'
//    )]
    public ?string $password;
}
