<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Core\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Enum\Status;

class AccountFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = $this->getReference('user_john', User::class);
        $user2 = $this->getReference('user_jane', User::class);
        $user3 = $this->getReference('user_alice', User::class);

        $account1 = new Account('Main Account John', 1000.0, $user1, Status::ACTIVE);
        $manager->persist($account1);

        $account2 = new Account('Main Account Jane', 1500.0, $user2, Status::ACTIVE);
        $manager->persist($account2);

        $account3 = new Account('Main Account Alice', 2000.0, $user3, Status::ACTIVE);
        $manager->persist($account3);

        $manager->flush();
    }
}
