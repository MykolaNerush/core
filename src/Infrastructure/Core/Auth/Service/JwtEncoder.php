<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Auth\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final readonly class JwtEncoder
{
    public function __construct(
        private ParameterBagInterface $params
    )
    {
    }

    public function encode(array $payload): string
    {
        return JWT::encode(
            $payload,
            $this->params->get('jwt_secret'),
            'HS256'
        );
    }

    public function decode(string $token): array
    {
        try {
            return (array)JWT::decode(
                $token,
                new Key($this->params->get('jwt_secret'), 'HS256')
            );
        } catch (\Exception $e) {
            throw new \RuntimeException('Invalid token', 0, $e);
        }
    }
} 