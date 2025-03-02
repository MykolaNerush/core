<?php

declare(strict_types=1);

namespace App\Application\Query\Video\GetVideo;

use App\Application\Query\Shared\Item;
use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetVideoHandler
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
    )
    {
    }

    /**
     * @return Item<Video>
     */
    public function __invoke(GetVideoQuery $query): Item
    {
        $video = $this->videoRepository->getByUuid($query->uuid);
        return new Item($video);
    }
}
