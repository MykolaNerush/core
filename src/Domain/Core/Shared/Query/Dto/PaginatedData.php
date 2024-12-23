<?php

declare(strict_types=1);

namespace App\Domain\Core\Shared\Query\Dto;

final readonly class PaginatedData
{
    /**
     * @param array<string, string|mixed> $data
     */
    public function __construct(private array $data, private int $total)
    {
    }

    /**
     * @return array<string, string|mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
