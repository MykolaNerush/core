<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Core\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Enum\Status;
use Ramsey\Uuid\Uuid;

class AccountFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = $this->getReference('user_john', User::class);
        $user2 = $this->getReference('user_jane', User::class);
        $user3 = $this->getReference('user_alice', User::class);

        $account1 = new Account(Uuid::uuid4(), 'Main Account John', $user1, Status::ACTIVE);
        $manager->persist($account1);

        $account2 = new Account(Uuid::uuid4(), 'Main Account Jane', $user2, Status::ACTIVE);
        $manager->persist($account2);

        $account3 = new Account(Uuid::uuid4(), 'Main Account Alice', $user3, Status::ACTIVE);
        $manager->persist($account3);

        $manager->flush();
    }
}
