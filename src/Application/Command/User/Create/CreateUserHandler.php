<?php

declare(strict_types=1);

namespace App\Application\Command\User\Create;

use App\Domain\Core\User\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function __invoke(CreateUserCommand $command): string
    {
        $user = $this->userRepository->createUser(
            $command->name,
            $command->email,
            $command->password,
        );
        return $user->getId();
    }
}
