<?php

declare(strict_types=1);

namespace App\Domain\Core\Video\Validator\Constrains;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class DeleteVideoRequestConstraint extends Constraint
{
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
    public string $messageVideoNotExists = 'Video not exists.';
}