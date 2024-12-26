<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Users;

use Symfony\Component\Validator\Constraints as Assert;

class GetUserRequest
{
    #[Assert\Uuid]
    #[Assert\NotBlank(message: 'The uuid field is required.')]
    public string $uuid;
}
