<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Service\Auth;

use App\Domain\Core\User\Entity\User;

interface TokenService
{
    /**
     * @return array{token: string, refresh_token: string, expires_in: int}
     */
    public function createToken(User $user): array;

    public function validateToken(string $token): bool;

    public function getUserFromToken(string $token): array;

    /**
     * @return array{token: string, refresh_token: string, expires_in: int}
     */
    public function refreshToken(string $refreshToken): array;

    public function revokeToken(string $token): void;
}