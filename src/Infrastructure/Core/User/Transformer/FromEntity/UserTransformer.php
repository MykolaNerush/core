<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\User\Transformer\FromEntity;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\User\Entity\User;
use League\Fractal\TransformerAbstract;

final class UserTransformer extends TransformerAbstract
{
    /**
     * @return array<string, mixed>
     */
    public function transform(User $user): array
    {
        $accounts = [];

        if ($user->getAccounts()) {
            /* @var Account $account */
            foreach ($user->getAccounts() as $account) {
                $accounts[] = [
                    'uuid' => $account->getUuid(),
                    'accountName' => $account->getAccountName(),
                    'balance' => $account->getBalance(),
                    'createdAt' => $account->getCreatedAt(),
                    'updatedAt' => $account->getUpdatedAt(),
                    'deletedAt' => $account->getDeletedAt(),
                    'status' => $account->getStatus(),
                ];
            }
        }

        return [
            'id' => $user->getUuid(),
            'uuid' => $user->getUuid(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'status' => $user->getStatus()->label(),
            'account' => $accounts,
            'timestamps' => [
                'createdAt' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $user->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
