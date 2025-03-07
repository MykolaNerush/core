<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\User;

use App\Domain\Core\User\Validator\Constraints\ConfirmEmailRequestConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[GroupSequence(['ConfirmEmailRequest', 'Custom'])]
#[ConfirmEmailRequestConstraint(groups: ['Custom'])]
class ConfirmEmailRequest
{
    private const string MESSAGE = 'Invalid confirmation link';

    #[Assert\Uuid(message: self::MESSAGE, strict: true)]
    public string $token;

    #[Assert\NotBlank(message: self::MESSAGE)]
    public string $email;

    #[Assert\NotBlank(message: self::MESSAGE)]
    public string $signature;
}