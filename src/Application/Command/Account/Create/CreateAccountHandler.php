<?php

declare(strict_types=1);

namespace App\Application\Command\Account\Create;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Enum\Status;
use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\Domain\Shared\Query\Exception\NotFoundException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateAccountHandler
{
    public function __construct(
        private UserRepositoryInterface    $userRepository,
        private AccountRepositoryInterface $accountRepository,
    )
    {
    }

    public function __invoke(CreateAccountCommand $command): void
    {
        $uuid = Uuid::fromString($command->userUuid);
        //todo add get auth user
        $user = $this->userRepository->getByUuid($uuid);

        if (!$user) {
            throw new NotFoundException(lcfirst((new \ReflectionClass(User::class))->getShortName()));
        }

        $account = new Account(
            $command->accountName,
            $user,
            Status::NEW,
        );
        $this->accountRepository->create($account);
    }
}
