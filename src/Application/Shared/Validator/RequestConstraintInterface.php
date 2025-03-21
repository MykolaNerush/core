<?php

declare(strict_types=1);

namespace App\Application\Shared\Validator;

interface RequestConstraintInterface
{
    public function getTargets(): string;
    public function getMessage(): string;
} 