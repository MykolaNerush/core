<?php

declare(strict_types=1);

namespace App\Application\Query\Account\GetAccounts;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;
use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetAccountsHandler
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    )
    {
    }

    /**
     * @return Collection<Item<Account>>
     */
    public function __invoke(GetAccountsQuery $query): Collection
    {
        $result = $this->accountRepository->page(
            $query->routeGenerator,
            $query->page,
            $query->perPage,
            $query->order,
            $query->sort,
            $query->uuidSearch,
        );
        return new Collection($result);
    }
}
