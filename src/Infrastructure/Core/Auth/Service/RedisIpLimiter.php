<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Auth\Service;

use App\Domain\Core\Auth\Service\IpLimiterInterface;
use Predis\Client as Redis;

class RedisIpLimiter implements IpLimiterInterface
{
    private const string ATTEMPT_PREFIX = 'ip_attempt:';
    private const string WHITELIST_PREFIX = 'ip_whitelist:';
    private const int MAX_ATTEMPTS = 5;
    private const int WINDOW_SECONDS = 300; // 5 minutes

    public function __construct(
        private readonly Redis $redis
    ) {
    }

    public function isAllowed(string $ip): bool
    {
        if ($this->isWhitelisted($ip)) {
            return true;
        }

        $attempts = (int) $this->redis->get(self::ATTEMPT_PREFIX . $ip);
        return $attempts < self::MAX_ATTEMPTS;
    }

    public function increment(string $ip): void
    {
        $key = self::ATTEMPT_PREFIX . $ip;
        
        if (!$this->redis->exists($key)) {
            $this->redis->setex($key, self::WINDOW_SECONDS, 1);
        } else {
            $this->redis->incr($key);
        }
    }

    public function reset(string $ip): void
    {
        $this->redis->del(self::ATTEMPT_PREFIX . $ip);
    }

    public function isWhitelisted(string $ip): bool
    {
        return (bool) $this->redis->exists(self::WHITELIST_PREFIX . $ip);
    }
} 