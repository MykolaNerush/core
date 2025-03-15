<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Auth\Repository;

use App\Domain\Core\Auth\Repository\TokenRepositoryInterface;
use App\Domain\Core\Auth\Token\TokenInterface;
use App\Domain\Core\Auth\Token\TokenType;
use App\Infrastructure\Core\Auth\Token\JwtToken;
use Predis\Client as Redis;

class RedisTokenRepository implements TokenRepositoryInterface
{
    private const string TOKEN_PREFIX = 'token:';
    private const string BLACKLIST_PREFIX = 'blacklist:';
    private const int TOKEN_TTL = 3600; // 1 hour
    private const int BLACKLIST_TTL = 86400; // 24 hours

    public function __construct(
        private readonly Redis $redis
    )
    {
    }

    public function save(TokenInterface $token): void
    {
        $key = self::TOKEN_PREFIX . $token->getToken();
        $this->redis->setex(
            $key,
            self::TOKEN_TTL,
            json_encode([
                'userId' => $token->getUserId(),
                'expiresAt' => $token->getExpiresAt()->format('Y-m-d H:i:s'),
                'type' => $token->getType()->value
            ])
        );
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function findByToken(string $token): ?TokenInterface
    {
        $key = self::TOKEN_PREFIX . $token;
        $data = $this->redis->get($key);

        if (!$data) {
            return null;
        }

        $data = json_decode($data, true);

        return new JwtToken(
            $token,
            $data['userId'],
            new \DateTimeImmutable($data['expiresAt']),
            TokenType::from($data['type'])
        );
    }

    public function isBlacklisted(string $token): bool
    {
        return (bool)$this->redis->exists(self::BLACKLIST_PREFIX . $token);
    }

    public function blacklist(string $token): void
    {
        $this->redis->setex(
            self::BLACKLIST_PREFIX . $token,
            self::BLACKLIST_TTL,
            '1'
        );
    }
}