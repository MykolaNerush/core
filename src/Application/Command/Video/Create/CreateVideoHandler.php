<?php

declare(strict_types=1);

namespace App\Application\Command\Video\Create;

use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use App\Domain\Core\Video\Entity\Video;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateVideoHandler
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
    )
    {
    }
    public function __invoke(CreateVideoCommand $command): void
    {
        //todo add relation to User
        $video = new Video(
            $command->title,
            $command->description,
            $command->filePath,
            $command->thumbnailPath,
            $command->duration,
        );
        $this->videoRepository->create($video);
    }
}
