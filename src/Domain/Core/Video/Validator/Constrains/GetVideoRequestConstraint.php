<?php

declare(strict_types=1);

namespace App\Domain\Core\Video\Validator\Constrains;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class GetVideoRequestConstraint extends Constraint
{
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
    public string $messageVideoNotExists = 'Video not exists.';
}