<?php

declare(strict_types=1);

namespace App\Application\Command\Account\Update;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UpdateAccountHandler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    )
    {
    }

    public function __invoke(UpdateAccountCommand $command): void
    {
        $account = $this->accountRepository->getByUuid($command->currentUuid);
        /** @var Account $account */
        $account = $account->update(
            $command->accountName,
        );
        $this->accountRepository->update($account);
    }
}
