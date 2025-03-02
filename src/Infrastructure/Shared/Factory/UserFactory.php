<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Factory;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Enum\Status;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
class UserFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'email' => self::faker()->email(),
            'name' => self::faker()->firstName(33),
            'password' => self::faker()->password(1, 3),
            'status' => self::faker()->randomElement(Status::cases()),
        ];
    }
}
