<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\VideoComments\Transformer\FromEntity;

use App\Domain\Core\VideoComment\Entity\VideoComment;
use League\Fractal\TransformerAbstract;

final class VideoCommentsTransformer extends TransformerAbstract
{
    /**
     * @return array<string, mixed>
     */
    public function transform(VideoComment $videoComments): array
    {
        return [
            'id' => $videoComments->getUuid(),
            'uuid' => $videoComments->getUuid(),
            'comment' => $videoComments->getComment(),
            'user' => $videoComments->getUser(),
            'video' => $videoComments->getVideo(),
            'timestamps' => [
                'createdAt' => $videoComments->getCreatedAt()?->format('Y-m-d H:i:s'),
                'updatedAt' => $videoComments->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
