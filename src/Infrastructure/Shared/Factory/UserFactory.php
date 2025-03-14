<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Factory;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Enum\Status;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
class UserFactory extends PersistentProxyObjectFactory
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
    }

    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        $email = self::faker()->email();
        $name = self::faker()->firstName(33);

        return [
            'email' => $email,
            'name' => $name,
            'password' => '',
            'plainPassword' => 'test',
            'status' => Status::ACTIVE,
        ];
    }

    protected function initialize(): static
    {
        return $this->afterInstantiate(function (User $user) {
            if ($user->getPlainPassword()) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
            }
        });
    }

    private function hashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }
}
