<?php

declare(strict_types=1);

namespace App\Application\Query\VideoComments\GetVideoComments;

use App\Application\Query\Shared\Item;
use App\Domain\Core\VideoComment\Entity\VideoComment;
use App\Domain\Core\VideoComment\Repository\VideoCommentsRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetVideoCommentsHandler
{
    public function __construct(
        private VideoCommentsRepositoryInterface $videoCommentsRepository,
    )
    {
    }

    /**
     * @return Item<VideoComment>
     */
    public function __invoke(GetVideoCommentsQuery $query): Item
    {
        $video = $this->videoCommentsRepository->getByUuid($query->uuid);
        return new Item($video);
    }
}
