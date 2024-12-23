<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Repository;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;
use Ramsey\Uuid\UuidInterface;

interface AccountRepositoryInterface
{
    public function get(UuidInterface $uuid): Account;

    public function create(Account $account): void;

    public function page(): PaginatedData;
}
