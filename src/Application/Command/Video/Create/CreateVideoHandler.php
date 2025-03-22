<?php

declare(strict_types=1);

namespace App\Application\Command\Video\Create;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\Domain\Core\UserVideo\Entity\UserVideo;
use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use App\Infrastructure\Shared\Services\Video\VideoUploader;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateVideoHandler
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
        private VideoUploader            $videoUploader,
        private Security                 $security,
    )
    {
    }

    public function __invoke(
        CreateVideoCommand $command,
    ): string
    {
        $videoFilePath = $this->videoUploader->upload($command->videoFile);
        $video = new Video(
            title: $command->title,
            description: $command->description,
            filePath: $videoFilePath,
            thumbnailPath: $command->thumbnailPath,
            duration: $command->duration,
        );
        $this->videoRepository->create($video);

        /* @var User $user */
        $user = $this->security->getUser();

        $userVideo = new UserVideo(
            user: $user,
            video: $video,
        );
        $this->videoRepository->createUerVideo($userVideo);
        return $video->getId();
    }
}
