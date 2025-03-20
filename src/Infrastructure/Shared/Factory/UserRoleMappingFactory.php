<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Factory;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Entity\UserRole;
use App\Domain\Core\User\Entity\UserRoleMapping;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use \App\Domain\Core\Account\Enum\Status;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
class UserRoleMappingFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return UserRoleMapping::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'user' => UserFactory::new(),
            'role' => UserRoleFactory::new(),
        ];
    }
}
