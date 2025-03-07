<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Security;

use Firebase\JWT\JWT;
use App\Domain\Core\User\Entity\User;

final class JWTTokenGenerator
{
    //todo get from config
    private string $secret = 'your_secret_key';

    public function generateToken(User $user): string
    {
        $payload = [
            'uuid' => $user->getUuid()->toString(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'exp' => time() + 3600, // 1 година
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }
}
