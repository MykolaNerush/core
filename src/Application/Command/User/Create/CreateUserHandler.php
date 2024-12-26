<?php

declare(strict_types=1);

namespace App\Application\Command\User\Create;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Enum\Status;
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
    public function __invoke(CreateUserCommand $command): void
    {
        $user = new User(
            $command->uuid,
            $command->name,
            $command->email,
            $command->password,
            Status::NEW,
        );
        $this->userRepository->create($user);
    }
}
