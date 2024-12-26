<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUser;

use App\Application\Query\Shared\Item;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function __invoke(GetUserQuery $query): Item
    {
        $user = $this->userRepository->getByUuid($query->uuid);
        return new Item($user);
    }
}
