<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Account\Transformer\FromEntity;

use App\Domain\Core\Account\Entity\Account;
use App\Domain\Core\User\Entity\User;
use League\Fractal\TransformerAbstract;

final class AccountTransformer extends TransformerAbstract
{
    /**
     * @return array<string, mixed>
     */
    public function transform(Account $account): array
    {
        return [
            'id' => $account->getUuid(),
            'uuid' => $account->getUuid(),
            'name' => $account->getAccountName(),
            'status' => $account->getStatus()->label(),
            'user' => $account->getUser()->serialize(),
            'timestamps' => [
                'createdAt' => $account->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $account->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ],
        ];
    }
}
