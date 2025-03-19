<?php

declare(strict_types=1);

namespace App\Domain\Shared\Entity;

interface OwnableInterface
{
    public function getOwnerId(): string;
}