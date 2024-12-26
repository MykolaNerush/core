<?php

declare(strict_types=1);

namespace App\Application\Command\User\Update;

use App\Domain\Core\User\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UpdateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->userRepository->getByUuid($command->currentUuid);
        $user->update(
            $command->name,
            $command->email,
            $command->password,
        );
        $this->userRepository->update($user);
    }
}
