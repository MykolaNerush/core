<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Enum;

enum Status: string
{
    case NEW = 'new';
    case BLOCKED = 'blocked';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'New',
            self::BLOCKED => 'Blocked',
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::SUSPENDED => 'Suspended',
        };
    }
}
