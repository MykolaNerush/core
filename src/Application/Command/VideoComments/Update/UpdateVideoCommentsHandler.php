<?php

declare(strict_types=1);

namespace App\Application\Command\VideoComments\Update;

use App\Infrastructure\Core\VideoComments\Repository\VideoCommentsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UpdateVideoCommentsHandler
{
    public function __construct(
        private VideoCommentsRepository $videoCommentsRepository,
    )
    {
    }

    public function __invoke(UpdateVideoCommentsCommand $command): void
    {
        $video = $this->videoCommentsRepository->getByUuid($command->currentUuid);
        $video->update(
            $command->comment,
        );
        $this->videoCommentsRepository->update($video);
    }
}
