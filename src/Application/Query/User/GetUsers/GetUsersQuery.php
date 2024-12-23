<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUsers;

use App\Application\Query\Shared\Queries\BasePageQuery;

final class GetUsersQuery extends BasePageQuery
{
    public function __construct(
        public         $routeGenerator,
        public int     $page = BasePageQuery::PAGE,
        public int     $perPage = BasePageQuery::PER_PAGE,
        public string  $order = BasePageQuery::ORDER,
        public string  $sort = BasePageQuery::SORT,
        public ?string $uuid = null,
        public ?string $emailSearch = null,
    )
    {
        parent::__construct($routeGenerator, $page, $perPage, $order, $sort, $uuid);
    }
}
