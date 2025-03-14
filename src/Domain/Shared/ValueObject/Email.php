<?php

namespace App\Domain\Shared\ValueObject;

use App\Infrastructure\Shared\Exception\InvalidEmailException;
use Assert\Assertion;
use Assert\AssertionFailedException;
final class Email
{
    private const int MAX_LENGTH = 255;
    private const string PATTERN = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    private string $value;

    public function __construct(string $email)
    {
        try {
            Assertion::notEmpty($email, 'Email cannot be empty');
            Assertion::maxLength($email, self::MAX_LENGTH, 'Email is too long');
            Assertion::regex($email, self::PATTERN, 'Invalid email format');
            $this->validateDomain($email);

            $this->value = $this->normalize($email);

        } catch (\Assert\InvalidArgumentException|AssertionFailedException $e ) {
            throw new InvalidEmailException($e->getMessage());
        }
    }

    private function validateDomain(string $email): void
    {
        $domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            throw new InvalidEmailException('Domain does not exist');
        }
        $disposableDomains = ['tempmail.com', 'throwaway.com']; // розширити список
        if (in_array($domain, $disposableDomains)) {
            throw new InvalidEmailException('Disposable email addresses are not allowed');
        }
    }

    private function normalize(string $email): string
    {
        $email = mb_strtolower($email);
        return trim($email);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->getValue();
    }

    public function getDomain(): string
    {
        return substr(strrchr($this->value, "@"), 1);
    }

    public function getLocalPart(): string
    {
        return strstr($this->value, '@', true);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}