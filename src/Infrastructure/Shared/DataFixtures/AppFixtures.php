<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\DataFixtures;

use App\Infrastructure\Shared\Factory\AccountFactory;
use App\Infrastructure\Shared\Factory\UserFactory;
use App\Infrastructure\Shared\Factory\UserVideoFactory;
use App\Infrastructure\Shared\Factory\VideoCommentFactory;
use App\Infrastructure\Shared\Factory\VideoFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = UserFactory::createMany(3);
        $videos = VideoFactory::createMany(3);

        $manager->flush();
        $manager->clear();

        AccountFactory::createMany(3, function () use (&$users) {
            return [
                'user' => $users[array_rand($users)]
            ];
        });
        // unique relations between User and Video
        $userVideoCombinations = [];
        foreach ($users as $user) {
            foreach ($videos as $video) {
                $userVideoCombinations[] = ['user' => $user, 'video' => $video];
            }
        }

        shuffle($userVideoCombinations);
        UserVideoFactory::createMany(3, function () use (&$userVideoCombinations) {
            return array_pop($userVideoCombinations);
        });

        // We also make comments unique for different videos
        $videoCommentCombinations = [];
        foreach ($users as $user) {
            foreach ($videos as $video) {
                $videoCommentCombinations[] = ['video' => $video, 'user' => $user];
            }
        }

        shuffle($videoCommentCombinations);
        VideoCommentFactory::createMany(3, function () use (&$videoCommentCombinations) {
            return array_pop($videoCommentCombinations);
        });

        $manager->flush();
    }

}
