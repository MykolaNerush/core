<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Service\Auth;

use App\Domain\Core\User\Entity\User;

interface TokenService
{
    public function createToken(User $user): string;

    public function validateToken(string $token): bool;

    public function getUserFromToken(string $token): array;
}