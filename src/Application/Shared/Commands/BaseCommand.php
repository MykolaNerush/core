<?php

declare(strict_types=1);

namespace App\Application\Shared\Commands;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class BaseCommand
{
    /**
     * Get instance of UuidInterface from string or null.
     */
    protected function getUuidOrNull(?string $param): ?UuidInterface
    {
        if (null === $param) {
            return null;
        }
        try {
            return Uuid::fromString($param);
        } catch (InvalidUuidStringException) {
            return null;
        }
    }
}
