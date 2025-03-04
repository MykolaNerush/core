<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUsers;

use App\Application\Query\Shared\Queries\BasePageQuery;
use Ramsey\Uuid\UuidInterface;

final class GetUsersQuery extends BasePageQuery
{
    public function __construct(
        public \Closure       $routeGenerator,
        public int            $page = BasePageQuery::PAGE,
        public int            $perPage = BasePageQuery::PER_PAGE,
        public string         $order = BasePageQuery::ORDER,
        public string         $sort = BasePageQuery::SORT,
        public ?UuidInterface $uuid = null,
        public ?string        $email = null,
    )
    {
        parent::__construct($routeGenerator, $page, $perPage, $order, $sort, $uuid);
    }
}
