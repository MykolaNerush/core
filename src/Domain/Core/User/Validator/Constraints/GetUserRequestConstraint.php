<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class GetUserRequestConstraint extends Constraint
{
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
    public string $messageAccountNotExists = 'User not exists.';
}