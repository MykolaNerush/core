<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Service;

interface IpLimiterInterface
{
    public function isAllowed(string $ip): bool;
    public function increment(string $ip): void;
    public function reset(string $ip): void;
    public function isWhitelisted(string $ip): bool;
} 