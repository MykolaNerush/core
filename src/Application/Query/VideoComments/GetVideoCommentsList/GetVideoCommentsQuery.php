<?php

declare(strict_types=1);

namespace App\Application\Query\VideoComments\GetVideoCommentsList;

use App\Application\Query\Shared\Queries\BasePageQuery;

final class GetVideoCommentsQuery extends BasePageQuery
{
    public function __construct(
        public \Closure $routeGenerator,
        public int      $page = BasePageQuery::PAGE,
        public int      $perPage = BasePageQuery::PER_PAGE,
        public string   $order = BasePageQuery::ORDER,
        public string   $sort = BasePageQuery::SORT,
        public ?string  $uuid = null,
        public ?string  $comment = null,
    )
    {
        parent::__construct($routeGenerator, $page, $perPage, $order, $sort, $uuid);
    }
}
