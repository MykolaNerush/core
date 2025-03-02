<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Factory;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\User\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
class AccountFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Account::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'accountName' => self::faker()->text(255),
            'balance' => self::faker()->randomNumber(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'status' => self::faker()->randomElement(\App\Domain\Core\Account\Enum\Status::cases()),
        ];
    }
}
