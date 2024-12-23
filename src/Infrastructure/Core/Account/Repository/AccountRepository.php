<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Account\Repository;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;
use App\Infrastructure\Shared\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class AccountRepository extends MysqlRepository implements AccountRepositoryInterface
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
        $this->class = Account::class;
        parent::__construct($this->em);
    }

    public function get(UuidInterface $uuid): Account
    {
        //todo implementation
    }

    public function create(Account $account): void
    {
        //todo implementation
    }

    public function page(): PaginatedData
    {
        //todo implementation
    }
}
