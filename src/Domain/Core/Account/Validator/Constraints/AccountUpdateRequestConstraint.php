<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AccountUpdateRequestConstraint extends Constraint
{
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
    public string $messageAccountNotExists = 'Account not exists.';
}