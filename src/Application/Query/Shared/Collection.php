<?php

declare(strict_types=1);

namespace App\Application\Query\Shared;

use App\Domain\Core\Shared\Query\Dto\PaginatedData;

/**
 * @template T
 */
readonly class Collection
{
    public function __construct(
        public PaginatedData $PaginatedData,
    )
    {
    }
}
