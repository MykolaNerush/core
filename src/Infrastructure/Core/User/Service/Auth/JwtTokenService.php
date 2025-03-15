<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\User\Service\Auth;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\Domain\Core\User\Service\Auth\TokenService;
use App\Domain\Core\Auth\Repository\TokenRepositoryInterface;
use App\Domain\Core\Auth\Token\Token;
use App\Domain\Core\Auth\Token\TokenType;
use App\Infrastructure\Core\Auth\Service\JwtEncoder;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final readonly class JwtTokenService implements TokenService
{
    public function __construct(
        private JwtEncoder               $jwtEncoder,
        private ParameterBagInterface    $params,
        private TokenRepositoryInterface $tokenRepository,
        private UserRepositoryInterface  $userRepository
    )
    {
    }

    public function createToken(User $user): array
    {
        $jwtTtl = (int)$this->params->get('jwt_ttl');
        $refreshTtl = $jwtTtl * 2;

        $accessToken = $this->generateAccessToken($user, $jwtTtl);
        $refreshTokenString = $this->generateRefreshToken($user, $refreshTtl);

        $refreshToken = new Token(
            $refreshTokenString,
            [
                'user_id' => $user->getId(),
                'type' => TokenType::REFRESH->value,
                'iat' => time(),
                'exp' => time() + $refreshTtl,
                'iss' => $this->params->get('jwt_issuer')
            ],
            $user->getId(),
            TokenType::REFRESH,
            time() + $refreshTtl
        );

        // Store refresh token
        $this->tokenRepository->save($refreshToken);

        return [
            'token' => $accessToken,
            'refresh_token' => $refreshTokenString,
            'expires_in' => $jwtTtl,
        ];
    }

    public function validateToken(string $token): bool
    {
        try {
            $this->jwtEncoder->decode($token);
            return true;
        } catch (\Exception) {
            return false;
        }
    }

    public function getUserFromToken(string $token): array
    {
        return $this->jwtEncoder->decode($token);
    }

    public function refreshToken(string $refreshToken): array
    {
        $token = $this->tokenRepository->findByToken($refreshToken);

        if (!$token || $token->getType() !== TokenType::REFRESH || $token->isExpired()) {
            throw new \RuntimeException('Invalid refresh token');
        }


        $user = $this->userRepository->findOneBy(['uuid' => Uuid::fromString($token->getUserId())->getBytes()]);
        if (!$user) {
            throw new \RuntimeException('User not found');
        }
        // Generate new tokens
        $jwtTtl = (int)$this->params->get('jwt_ttl');
        $refreshTtl = $jwtTtl * 2;

        $accessToken = $this->generateAccessToken($user, $jwtTtl);
        $newRefreshTokenString = $this->generateRefreshToken($user, $refreshTtl);
        $newRefreshToken = new Token(
            $newRefreshTokenString,
            [
                'user_id' => $user->getId(),
                'type' => TokenType::REFRESH->value,
                'iat' => time(),
                'exp' => time() + $refreshTtl,
                'iss' => $this->params->get('jwt_issuer')
            ],
            $user->getId(),
            TokenType::REFRESH,
            time() + $refreshTtl
        );

        // Remove old refresh token and store new one
        $this->tokenRepository->blacklist($refreshToken);
        $this->tokenRepository->save($newRefreshToken);

        return [
            'token' => $accessToken,
            'refresh_token' => $newRefreshTokenString,
            'expires_in' => $jwtTtl,
        ];
    }

    public function revokeToken(string $token): void
    {
        $this->tokenRepository->blacklist($token);
    }

    private function generateAccessToken(User $user, int $ttl): string
    {
        $payload = [
            'sub' => $user->getId(),
            'username' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'iat' => time(),
            'exp' => time() + $ttl,
            'iss' => $this->params->get('jwt_issuer')
        ];

        return $this->jwtEncoder->encode($payload);
    }

    private function generateRefreshToken(User $user, int $ttl): string
    {
        $payload = [
            'user_id' => $user->getId(),
            'iat' => time(),
            'exp' => time() + $ttl,
            'iss' => $this->params->get('jwt_issuer'),
            'type' => TokenType::REFRESH->value
        ];

        return $this->jwtEncoder->encode($payload);
    }
}