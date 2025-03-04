<?php

declare(strict_types=1);

namespace App\Application\Query\Account\GetAccounts;

use App\Application\Query\Shared\Queries\BasePageQuery;
use Ramsey\Uuid\UuidInterface;

final class GetAccountsQuery extends BasePageQuery
{
    public function __construct(
        public \Closure $routeGenerator,
        public int      $page = BasePageQuery::PAGE,
        public int      $perPage = BasePageQuery::PER_PAGE,
        public string   $order = BasePageQuery::ORDER,
        public string   $sort = BasePageQuery::SORT,
        public ?UuidInterface  $uuid = null,
//        public ?string  $emailSearch = null,
        public ?UuidInterface  $user = null,
    )
    {
        parent::__construct($routeGenerator, $page, $perPage, $order, $sort, $uuid);
    }
}
