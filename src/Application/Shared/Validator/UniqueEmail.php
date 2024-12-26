<?php

declare(strict_types=1);

namespace App\Application\Shared\Validator;

use Symfony\Component\Validator\Constraint;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class UniqueEmail extends Constraint
{
    public string $message = 'Email already exists.';
}