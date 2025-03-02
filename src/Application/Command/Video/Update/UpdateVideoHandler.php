<?php

declare(strict_types=1);

namespace App\Application\Command\Video\Update;

use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UpdateVideoHandler
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
    )
    {
    }

    public function __invoke(UpdateVideoCommand $command): void
    {
        $video = $this->videoRepository->getByUuid($command->currentUuid);
        $video->update(
            $command->title,
            $command->description,
            $command->filePath,
            $command->thumbnailPath,
        );
        $this->videoRepository->update($video);
    }
}
