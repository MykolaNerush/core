<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Auth\Cache;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class AuthTokenCache
{
    private const CACHE_KEY_PREFIX = 'auth_token_';
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private readonly CacheItemPoolInterface $cache
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $username, string $password): ?string
    {
        $key = $this->getCacheKey($username, $password);
        $item = $this->cache->getItem($key);

        if (!$item->isHit()) {
            return null;
        }

        return $item->get();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function set(string $username, string $password, string $token): void
    {
        $key = $this->getCacheKey($username, $password);
        $item = $this->cache->getItem($key);
        $item->set($token);
        $item->expiresAfter(self::CACHE_TTL);
        $this->cache->save($item);
    }

    private function getCacheKey(string $username, string $password): string
    {
        return self::CACHE_KEY_PREFIX . md5($username . ':' . $password);
    }
} 