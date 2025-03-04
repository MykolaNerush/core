<?php

namespace App\Infrastructure\Shared\Factory;

use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\Video\Enum\Status;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Video>
 */
final class VideoFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Video::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'duration' => self::faker()->randomNumber(),
            'status' => self::faker()->randomElement(Status::cases()),
            'title' => self::faker()->text(33),
            'description' => self::faker()->text(133),
            'filePath' => self::faker()->filePath(),
            'thumbnailPath' => self::faker()->filePath(),
        ];
    }
}
