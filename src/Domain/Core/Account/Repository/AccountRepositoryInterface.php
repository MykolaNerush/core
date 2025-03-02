<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Repository;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;
use App\Domain\Core\User\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface AccountRepositoryInterface
{
    public function create(Account $account): void;
    public function getByUuid(UuidInterface $uuid): mixed;

    public function update(Account $account): void;
    public function remove(Account $account): void;
    public function page(
        callable      $routeGenerator,
        int           $page,
        int           $perPage,
        string        $order,
        string        $sort,
        UuidInterface $uuidSearch = null,
    ): PaginatedData;
}
