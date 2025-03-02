<?php

declare(strict_types=1);

namespace App\Application\Command\Video\Delete;

use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteVideoHandler
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
    )
    {
    }

    public function __invoke(DeleteVideoCommand $command): void
    {
        $video = $this->videoRepository->getByUuid($command->uuid);
        if ($video instanceof Video) {
            $this->videoRepository->remove($video);
        }
    }
}
