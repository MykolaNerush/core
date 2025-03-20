<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CreateAccountRequestConstraint extends Constraint
{
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public string $messageAccountExists = 'Account already exists.';
}