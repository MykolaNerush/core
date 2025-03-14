<?php

namespace App\Infrastructure\Core\User\Service\Auth;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Service\Auth\TokenService;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

final readonly class JwtTokenService implements TokenService
{
    public function __construct(
        private JWTEncoderInterface $jwtEncoder
    ) {}

    public function createToken(User $user): string
    {
        $payload = [
            'sub' => $user->getId(),
            'username' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'iat' => time(),
            'exp' => time() + 3600
        ];

        return $this->jwtEncoder->encode($payload);
    }

    public function validateToken(string $token): bool
    {
        try {
            $this->jwtEncoder->decode($token);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUserFromToken(string $token): array
    {
        return (array)$this->jwtEncoder->decode($token);
    }
}