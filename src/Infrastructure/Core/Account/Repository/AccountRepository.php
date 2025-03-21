<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Account\Repository;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;
use App\Infrastructure\Core\Account\Transformer\FromEntity\AccountTransformer;
use App\Infrastructure\Shared\Query\Repository\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends MysqlRepository<Account>
 */
final class AccountRepository extends MysqlRepository implements AccountRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        $this->class = Account::class;
        parent::__construct($this->em);
    }

    public function page(
        callable       $routeGenerator,
        int            $page,
        int            $perPage,
        string         $order,
        string         $sort,
        ?UuidInterface $uuid = null,
        ?UuidInterface $user = null,
    ): PaginatedData
    {
        return $this->getFilteredPaginatedData(
            $this->getFilteredQueryBuilder($page, $perPage, $order, $sort, [
                ['uuid', $uuid],
                ['user', $user],
            ]),
            $routeGenerator,
            new AccountTransformer(),
            'accounts'
        );
    }
}
