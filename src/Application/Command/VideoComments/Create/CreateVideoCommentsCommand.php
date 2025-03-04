<?php

declare(strict_types=1);

namespace App\Application\Command\VideoComments\Create;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\Video\Entity\Video;

readonly class CreateVideoCommentsCommand
{
    public function __construct(
        public Video  $video,
        public User   $user,
        public string $comment,
    )
    {
    }
}
