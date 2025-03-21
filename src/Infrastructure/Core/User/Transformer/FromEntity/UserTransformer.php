<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\User\Transformer\FromEntity;

use App\Domain\Core\User\Entity\User;
use League\Fractal\TransformerAbstract;

final class UserTransformer extends TransformerAbstract
{
    /**
     * @return array<string, mixed>
     */
    public function transform(User $user): array
    {
        $account = $user->getAccount();

        return [
            'id' => $user->getUuid(),
            'uuid' => $user->getUuid(),
            'roles' => $user->getRoles(),
            'videos' => $user->getUserVideos(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'status' => $user->getStatus()->label(),
            'account' => [
                'uuid' => $account?->getUuid(),
                'accountName' => $account?->getAccountName(),
                'balance' => $account?->getBalance(),
                'createdAt' => $account?->getCreatedAt(),
                'updatedAt' => $account?->getUpdatedAt(),
                'deletedAt' => $account?->getDeletedAt(),
                'status' => $account?->getStatus(),
            ],
            'timestamps' => [
                'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
                'updatedAt' => $user->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
