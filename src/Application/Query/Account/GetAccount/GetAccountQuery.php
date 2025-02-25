<?php

declare(strict_types=1);

namespace App\Application\Query\Account\GetAccount;

use App\Application\Query\Shared\Queries\BaseQuery;
use Ramsey\Uuid\UuidInterface;

final class GetAccountQuery extends BaseQuery
{
    public function __construct(public UuidInterface $uuid)
    {
    }
}
