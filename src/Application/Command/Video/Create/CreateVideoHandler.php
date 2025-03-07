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
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateVideoHandler
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
        private VideoUploader            $videoUploader,
        private UserRepositoryInterface  $userRepository, //todo remove after add auth user
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

        //todo add get auth user
        $userUuid = 'ea24298e-7832-4c93-b345-de980b6783aa';
        $uuid = Uuid::fromString($userUuid);
        /* @var User $user */
        $user = $this->userRepository->getByUuid($uuid);

        $userVideo = new UserVideo(
            user: $user,
            video: $video,
        );
        $this->videoRepository->createUerVideo($userVideo);
echo "<pre>";
var_export($video->getId());
var_export($userVideo->getVideo()->getId());
var_export($userVideo->getUser()->getId());
die();
        return $video->getId();
    }
}
