<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Factory;

use App\Domain\Core\VideoComment\Entity\VideoComment;
use App\Domain\Core\VideoComment\Enum\CommentStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class VideoCommentFactory extends PersistentProxyObjectFactory
{
    protected function defaults(): array
    {
        return [
            'video' => VideoFactory::new(),
            'user' => UserFactory::new(),
            'comment' => self::faker()->text,
            'status' => CommentStatus::PENDING,
        ];
    }

    public static function class(): string
    {
        return VideoComment::class;
    }

}