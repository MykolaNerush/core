<?php

declare(strict_types=1);

namespace App\Application\Command\User\Delete;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function __invoke(DeleteUserCommand $command): void
    {
        $user = $this->userRepository->getByUuid($command->uuid);
        if ($user instanceof User) {
            $this->userRepository->delete($user);
        }
    }
}
