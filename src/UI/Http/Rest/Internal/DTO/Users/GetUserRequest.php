<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Users;

use Symfony\Component\Validator\Constraints as Assert;

class GetUserRequest
{
    public string $uuid;
}
