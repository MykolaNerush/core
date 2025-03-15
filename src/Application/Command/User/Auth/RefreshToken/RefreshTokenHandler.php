<?php

declare(strict_types=1);

namespace App\Application\Command\User\Auth\RefreshToken;

use App\Domain\Core\User\Service\Auth\TokenService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RefreshTokenHandler
{
    public function __construct(private TokenService $tokenService)
    {
    }

    public function __invoke(RefreshTokenCommand $command): array
    {
        return $this->tokenService->refreshToken($command->refreshToken);
    }
}