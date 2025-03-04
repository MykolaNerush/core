<?php

declare(strict_types=1);

namespace App\Application\Query\VideoComments\GetVideoCommentsList;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;
use App\Domain\Core\VideoComment\Entity\VideoComment;
use App\Infrastructure\Core\VideoComments\Repository\VideoCommentsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetVideoCommentsHandler
{
    public function __construct(
        private VideoCommentsRepository $videoCommentsRepository,
    )
    {
    }

    /**
     * @return Collection<Item<VideoComment>>
     */
    public function __invoke(GetVideoCommentsQuery $query): Collection
    {
        $result = $this->videoCommentsRepository->page(
            $query->routeGenerator,
            $query->page,
            $query->perPage,
            $query->order,
            $query->sort,
            $query->uuid,
            $query->comment,
        );
        return new Collection($result);
    }
}
