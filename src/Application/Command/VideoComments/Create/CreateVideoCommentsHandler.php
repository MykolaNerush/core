<?php

declare(strict_types=1);

namespace App\Application\Command\VideoComments\Create;

use App\Domain\Core\VideoComment\Entity\VideoComment;
use App\Domain\Core\VideoComment\Repository\VideoCommentsRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateVideoCommentsHandler
{
    public function __construct(
        private VideoCommentsRepositoryInterface $videoCommentsRepository,
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
        $this->videoCommentsRepository->create($video);
        return $video->getId();
    }
}
