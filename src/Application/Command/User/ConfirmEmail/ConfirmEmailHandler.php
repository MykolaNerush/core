<?php

declare(strict_types=1);

namespace App\Application\Command\User\ConfirmEmail;

use App\Domain\Core\User\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class ConfirmEmailHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function __invoke(ConfirmEmailCommand $command): void
    {
        $this->userRepository->confirmEmail(urldecode($command->email));
    }
}
