<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Auth\Token;

use App\Domain\Core\Auth\Token\TokenInterface;
use App\Domain\Core\Auth\Token\TokenType;
use DateTimeImmutable;

class JwtToken implements TokenInterface
{
    private string $token;
    private string $userId;
    private DateTimeImmutable $expiresAt;
    private TokenType $type;

    public function __construct(
        string $token,
        string $userId,
        DateTimeImmutable $expiresAt,
        TokenType $type
    ) {
        $this->token = $token;
        $this->userId = $userId;
        $this->expiresAt = $expiresAt;
        $this->type = $type;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt < new DateTimeImmutable();
    }

    public function getType(): TokenType
    {
        return $this->type;
    }
} 