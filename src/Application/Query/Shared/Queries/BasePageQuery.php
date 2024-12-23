<?php

declare(strict_types=1);

namespace App\Application\Query\Shared\Queries;

use App\Application\Shared\Commands\BaseCommand;
use Ramsey\Uuid\UuidInterface;

abstract class BasePageQuery extends BaseCommand
{
    public const int PAGE = 1;
    public const int PER_PAGE = 50;
    public const int MAX_PER_PAGE = 500;
    public const string ORDER = 'DESC';
    public const string SORT = 'createdAt';

    public ?UuidInterface $uuidSearch = null;

    public function __construct(
        public         $routeGenerator,
        public int     $page = self::PAGE,
        public int     $perPage = self::PER_PAGE,
        public string  $order = self::ORDER,
        public string  $sort = self::SORT,
        public ?string $uuid = null
    )
    {
        $this->perPage = min($perPage, self::MAX_PER_PAGE);
        $this->uuidSearch = $this->getUuidOrNull($uuid);
    }
}
