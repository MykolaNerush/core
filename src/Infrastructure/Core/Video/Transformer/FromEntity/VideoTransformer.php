<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Video\Transformer\FromEntity;

use App\Domain\Core\Video\Entity\Video;
use League\Fractal\TransformerAbstract;

final class VideoTransformer extends TransformerAbstract
{
    /**
     * @return array<string, mixed>
     */
    public function transform(Video $video): array
    {
        return [
            'id' => $video->getUuid(),
            'uuid' => $video->getUuid(),
            'title' => $video->getTitle(),
            'description' => $video->getDescription(),
            'filePath' => $video->getFilePath(),
            'thumbnailPath' => $video->getThumbnailPath(),
            'getDuration' => $video->getDuration(),
            'duration' => $video->getDuration(),
            'status' => $video->getStatus()->label(),
//todo add
//            'user' => $video->getuser(),
            'timestamps' => [
                'createdAt' => $video->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $video->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
