<?php

declare(strict_types=1);

namespace App\Application\Query\VideoComments\GetVideoComments;

use App\Application\Query\Shared\Queries\BaseQuery;
use Ramsey\Uuid\UuidInterface;

final class GetVideoCommentsQuery extends BaseQuery
{
    public function __construct(public UuidInterface $uuid)
    {
    }
}
