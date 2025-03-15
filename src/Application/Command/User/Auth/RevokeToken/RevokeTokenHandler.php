<?php

declare(strict_types=1);

namespace App\Application\Command\User\Auth\RevokeToken;

use App\Domain\Core\User\Service\Auth\TokenService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RevokeTokenHandler
{
    public function __construct(private TokenService $tokenService)
    {
    }

    public function __invoke(RevokeTokenCommand $command): void
    {
        $this->tokenService->revokeToken($command->token);
    }
}