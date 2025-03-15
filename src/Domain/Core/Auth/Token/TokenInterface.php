<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Token;

interface TokenInterface
{
    public function getToken(): string;
    public function getUserId(): string;
    public function getExpiresAt(): \DateTimeImmutable;
    public function isExpired(): bool;
    public function getType(): TokenType;
} 