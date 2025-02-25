<?php

declare(strict_types=1);

namespace App\Application\Command\Account\Create;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Enum\Status;
use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateAccountHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AccountRepositoryInterface $accountRepository,
    )
    {
    }
    public function __invoke(CreateAccountCommand $command): void
    {
        //todo get auth user !!!!
        $uuid = '724afee0-d001-47e5-a9d4-29a3f19b81b8';
        $uuid = Uuid::fromString($uuid);
        $user = $this->userRepository->getByUuid($uuid);

        $user = new Account(
            $command->uuid,
            $command->name,
            $user,
            Status::NEW,
        );
        $this->accountRepository->create($user);
    }
}
