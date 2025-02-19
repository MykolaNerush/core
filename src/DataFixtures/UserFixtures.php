<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Enum\Status;
use Ramsey\Uuid\Uuid;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new User(Uuid::uuid4(), 'Test', 'test@gmail.com', 'test', Status::ACTIVE);
        $manager->persist($user1);

        $user2 = new User(Uuid::uuid4(), 'Jane Doe', 'jane@example.com', 'password', Status::ACTIVE);
        $manager->persist($user2);

        $user3 = new User(Uuid::uuid4(), 'Alice Smith', 'alice@example.com', 'password', Status::ACTIVE);
        $manager->persist($user3);

        $manager->flush();

        $this->addReference('user_john', $user1);
        $this->addReference('user_jane', $user2);
        $this->addReference('user_alice', $user3);
    }
}
