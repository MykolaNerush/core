<?php

declare(strict_types=1);

namespace App\Application\Query\Shared;

readonly class Collection
{
    /**
     * @param Item[] $data
     */
    public function __construct(
        public int   $page,
        public int   $perPage,
        public int   $total,
        public array $data
    )
    {
    }
}
