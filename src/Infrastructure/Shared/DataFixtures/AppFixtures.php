<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\DataFixtures;

use App\Infrastructure\Shared\Factory\AccountFactory;
use App\Infrastructure\Shared\Factory\UserFactory;
use App\Infrastructure\Shared\Factory\VideoFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = UserFactory::createMany(3);
        AccountFactory::createMany(3, function () use (&$users) {
            return [
                'user' => array_pop($users)
            ];
        });
        VideoFactory::createMany(3);
    }
}
