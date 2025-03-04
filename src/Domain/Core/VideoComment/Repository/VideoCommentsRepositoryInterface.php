<?php

declare(strict_types=1);

namespace App\Domain\Core\VideoComment\Repository;

use App\Domain\Core\VideoComment\Entity\VideoComment;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;

interface VideoCommentsRepositoryInterface
{
    public function getByUuid(UuidInterface $uuid): mixed;

    public function update(VideoComment $video): void;

    public function create(VideoComment $video): void;

    public function remove(VideoComment $video): void;

    public function page(
        callable       $routeGenerator,
        int            $page,
        int            $perPage,
        string         $order,
        string         $sort,
        ?UuidInterface $uuidSearch = null,
        ?string        $comment = null,
    ): PaginatedData;
}
