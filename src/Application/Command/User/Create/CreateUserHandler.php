<?php

declare(strict_types=1);

namespace App\Application\Command\User\Create;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateUserHandler
{
    public function __invoke(CreateUserCommand $command): void
    {
        //todo implementation
    }
}
