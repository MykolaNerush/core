<?php

declare(strict_types=1);

namespace App\Application\Shared\Validator;

use Symfony\Component\Validator\Constraint;

abstract class AbstractRequestConstraint extends Constraint implements RequestConstraintInterface
{
    public string $messageAccountNotExists = 'Account not exists.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function getMessage(): string
    {
        return $this->messageAccountNotExists;
    }
} 