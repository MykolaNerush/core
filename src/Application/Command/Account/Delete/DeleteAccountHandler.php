<?php

declare(strict_types=1);

namespace App\Application\Command\Account\Delete;

use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use App\Domain\Core\Account\Entity\Account;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteAccountHandler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    )
    {
    }

    public function __invoke(DeleteAccountCommand $command): void
    {
        $account = $this->accountRepository->getByUuid($command->uuid);
        if ($account instanceof Account) {
            $this->accountRepository->remove($account);
        }
    }
}
