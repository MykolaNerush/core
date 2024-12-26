<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUser;

use App\Application\Query\Shared\Queries\BaseQuery;
use Ramsey\Uuid\UuidInterface;

final class GetUserQuery extends BaseQuery
{
    public function __construct(public UuidInterface $uuid)
    {
    }
}
