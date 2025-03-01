<?php

declare(strict_types=1);

namespace App\Domain\Core\Video\Repository;

use App\Domain\Core\Video\Entity\Video;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;

interface VideoRepositoryInterface
{
    public function getByUuid(UuidInterface $uuid): mixed;
    public function update(Video $video): void;
    public function create(Video $video): void;
    public function page(
        callable      $routeGenerator,
        int           $page,
        int           $perPage,
        string        $order,
        string        $sort,
        UuidInterface $uuidSearch = null,
        string        $emailSearch = null
    ): PaginatedData;
}
