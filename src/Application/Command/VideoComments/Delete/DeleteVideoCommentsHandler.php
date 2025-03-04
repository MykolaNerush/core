<?php

declare(strict_types=1);

namespace App\Application\Command\VideoComments\Delete;

use App\Application\Command\Video\Delete\DeleteVideoCommand;
use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use App\Domain\Core\VideoComment\Entity\VideoComment;
use App\Domain\Core\VideoComment\Repository\VideoCommentsRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteVideoCommentsHandler
{
    public function __construct(
        private VideoCommentsRepositoryInterface $videoCommentsRepository,
    )
    {
    }

    public function __invoke(DeleteVideoCommentsCommand $command): void
    {
        $video = $this->videoCommentsRepository->getByUuid($command->uuid);
        if ($video instanceof VideoComment) {
            $this->videoCommentsRepository->remove($video);
        }
    }
}
