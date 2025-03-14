<?php

declare(strict_types=1);

namespace App\Domain\Core\User\ValueObject;

use App\Infrastructure\Shared\Exception\InvalidPasswordException;
use Assert\Assertion;
use Assert\AssertionFailedException;

final class HashedPassword
{
    private const int MIN_LENGTH = 8;
    private const int MAX_LENGTH = 72;
    private const int MIN_STRENGTH = 3;

    private string $hashedValue;

    private function __construct(string $hashedValue)
    {
        $this->hashedValue = $hashedValue;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromHash(string $hash): self
    {
        Assertion::notEmpty($hash, 'Password hash cannot be empty');
        return new self($hash);
    }

    public static function fromPlainPassword(string $password): self
    {
        try {
            self::validatePassword($password);

            $options = [
                'memory_cost' => 65536,  // 64MB
                'time_cost'   => 4,      // 4 iterations
                'threads'     => 3       // 3 threads
            ];

            $hash = password_hash($password, PASSWORD_ARGON2ID, $options);

            if ($hash === false) {
                throw new InvalidPasswordException('Failed to hash password');
            }

            return new self($hash);

        } catch (\Assert\InvalidArgumentException $e) {
            throw new InvalidPasswordException($e->getMessage());
        }
    }

    /**
     * @throws AssertionFailedException
     */
    private static function validatePassword(string $password): void
    {
        Assertion::notEmpty($password, 'Password cannot be empty');
        Assertion::minLength($password, self::MIN_LENGTH,
            sprintf('Password must be at least %d characters long', self::MIN_LENGTH));
        Assertion::maxLength($password, self::MAX_LENGTH,
            sprintf('Password cannot be longer than %d characters', self::MAX_LENGTH));

        $strength = 0;

        if (preg_match('/\d/', $password)) {
            $strength++;
        }

        if (preg_match('/[a-z]/', $password)) {
            $strength++;
        }

        if (preg_match('/[A-Z]/', $password)) {
            $strength++;
        }

        if (preg_match('/[^a-zA-Z\d]/', $password)) {
            $strength++;
        }

        if ($strength < self::MIN_STRENGTH) {
            throw new InvalidPasswordException(
                'Password must contain at least 3 of the following: numbers, lowercase letters, uppercase letters, special characters'
            );
        }

        $commonPasswords = ['password123', '12345678', 'qwerty123']; // розширити список
        if (in_array(strtolower($password), $commonPasswords)) {
            throw new InvalidPasswordException('This password is too common');
        }
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedValue);
    }

    public function needsRehash(): bool
    {
        return password_needs_rehash($this->hashedValue, PASSWORD_ARGON2ID);
    }

    public function getValue(): string
    {
        return $this->hashedValue;
    }
}