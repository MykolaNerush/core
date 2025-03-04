<?php

declare(strict_types=1);

namespace App\Application\Query\Video\GetVideos;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;
use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetVideosHandler
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
    )
    {
    }

    /**
     * @return Collection<Item<Video>>
     */
    public function __invoke(GetVideosQuery $query): Collection
    {
        $result = $this->videoRepository->page(
            $query->routeGenerator,
            $query->page,
            $query->perPage,
            $query->order,
            $query->sort,
            $query->uuid,
            $query->title,
            $query->description,
            $query->filePath,
            $query->thumbnailPath,
            $query->duration,
        );
        return new Collection($result);
    }
}
