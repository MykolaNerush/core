<?php

declare(strict_types=1);

namespace App\Domain\Shared\Security;

interface OwnedResourceInterface
{
    public function getOwnerId(): string;
} 