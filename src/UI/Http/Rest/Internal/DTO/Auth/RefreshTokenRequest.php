<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class RefreshTokenRequest
{
    #[Assert\NotBlank(message: 'The refreshToken field is required.')]
    public string $refreshToken;
}
