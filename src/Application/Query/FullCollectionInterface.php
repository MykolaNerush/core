<?php

declare(strict_types=1);

namespace App\Application\Query;

interface FullCollectionInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getData(): array;
}
