<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\DataFixtures;

use App\Domain\Core\User\Entity\UserRole;
use App\Domain\Core\User\Entity\UserRoleMapping;
use App\Infrastructure\Shared\Factory\AccountFactory;
use App\Infrastructure\Shared\Factory\UserFactory;
use App\Infrastructure\Shared\Factory\UserRoleFactory;
use App\Infrastructure\Shared\Factory\UserRoleMappingFactory;
use App\Infrastructure\Shared\Factory\UserVideoFactory;
use App\Infrastructure\Shared\Factory\VideoCommentFactory;
use App\Infrastructure\Shared\Factory\VideoFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = UserFactory::createOne([
            'email' => 'admin@gmail.com',
            'plainPassword' => 'test',
            'name' => 'admin',
        ]);

        $roles = array_map(fn($role) => UserRoleFactory::createOne(['role' => $role]), ['ROLE_ADMIN', 'ROLE_MANAGER', 'ROLE_MODERATOR']);
        foreach ($roles as $role) {
            UserRoleMappingFactory::createOne([
                'user' => $user,
                'role' => $role,
            ]);
        }

        $videos = VideoFactory::createMany(3);
        $manager->flush();
        $manager->clear();

        AccountFactory::createOne([
            'user' => $user,
        ]);

        foreach ($videos as $video) {
            UserVideoFactory::createOne([
                'user' => $user,
                'video' => $video,
            ]);
        }

        foreach ($videos as $video) {
            VideoCommentFactory::createOne([
                'video' => $video,
                'user' => $user,
            ]);
        }

        $manager->flush();
    }
}
