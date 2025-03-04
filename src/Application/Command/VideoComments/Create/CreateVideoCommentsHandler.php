<?php

declare(strict_types=1);

namespace App\Application\Command\VideoComments\Create;

use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use App\Domain\Core\VideoComment\Entity\VideoComment;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateVideoCommentsHandler
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
    )
    {
    }

    public function __invoke(CreateVideoCommentsCommand $command): string
    {
        $video = new VideoComment(
            $command->video,
            $command->user,
            $command->comment,
        );
        $this->videoRepository->create($video);
        return $video->getId();
    }
}
