<?php

declare(strict_types=1);

namespace App\Domain\Core\Shared\Query\Dto;

final readonly class PaginatedData
{
    /**
     * @param array<string, string|mixed> $data
     */
    public function __construct(
        private array $data,
    )
    {
    }

    /**
     * @return array<string, string|mixed>
     */
    public function getData(): array
    {
        return $this->data['data'];
    }

    /**
     * @return array<string, string|mixed>
     */
    public function getMeta(): array
    {
        return $this->data['meta'];
    }

    /**
     * @return array<string, string|mixed>
     */
    public function getLinks(): array
    {
        return $this->data['links'];
    }
}
