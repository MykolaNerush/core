<?php

declare(strict_types=1);

namespace App\Application\Query\Video\GetVideo;

use App\Application\Query\Shared\Queries\BaseQuery;
use Ramsey\Uuid\UuidInterface;

final class GetVideoQuery extends BaseQuery
{
    public function __construct(public UuidInterface $uuid)
    {
    }
}
