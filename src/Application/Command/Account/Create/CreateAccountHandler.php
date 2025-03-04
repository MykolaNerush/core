<?php

declare(strict_types=1);

namespace App\Application\Command\Account\Create;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Enum\Status;
use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateAccountHandler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    )
    {
    }

    public function __invoke(CreateAccountCommand $command): string
    {
        $account = new Account(
            $command->accountName,
            $command->user,
            Status::NEW,
        );
        $this->accountRepository->create($account);
        return $account->getId();
    }
}
