<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Validator\Constrains;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class RevokeTokenRequestConstraint extends Constraint
{
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
    public string $message = 'No token provided.';
}