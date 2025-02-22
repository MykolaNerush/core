<?php

declare(strict_types=1);

namespace App\Application\Query\Shared;

/**
 * @template T
 */
readonly class Collection
{
    /**
     * @param array<mixed, T> $data
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
