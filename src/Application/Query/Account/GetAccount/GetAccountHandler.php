<?php

declare(strict_types=1);

namespace App\Application\Query\Account\GetAccount;

use App\Application\Query\Shared\Item;
use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetAccountHandler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    )
    {
    }

    /**
     * @return Item<Account>
     */
    public function __invoke(GetAccountQuery $query): Item
    {
        $account = $this->accountRepository->getByUuid($query->uuid);
        return new Item($account);
    }
}
