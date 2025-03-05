<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Factory;

use App\Domain\Core\UserVideo\Entity\UserVideo;
use App\Domain\Core\UserVideo\Enum\Role;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class UserVideoFactory extends PersistentProxyObjectFactory
{
    protected function defaults(): array
    {
        return [
            'user' => UserFactory::new(),
            'video' => VideoFactory::new(),
            'role' => Role::VIEWER,
        ];
    }

    public static function class(): string
    {
        return UserVideo::class;
    }
}