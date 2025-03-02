<?php

declare(strict_types=1);

namespace App\Application\Query\Video\GetVideos;

use App\Application\Query\Shared\Queries\BasePageQuery;

final class GetVideosQuery extends BasePageQuery
{
    public function __construct(
        public \Closure $routeGenerator,
        public int      $page = BasePageQuery::PAGE,
        public int      $perPage = BasePageQuery::PER_PAGE,
        public string   $order = BasePageQuery::ORDER,
        public string   $sort = BasePageQuery::SORT,
        public ?string  $uuid = null,
        public ?string  $title = null,
        public ?string  $description = null,
        public ?string  $filePath = null,
        public ?string  $thumbnailPath = null,
        public ?int     $duration = null,
    )
    {
        parent::__construct($routeGenerator, $page, $perPage, $order, $sort, $uuid);
    }
}
