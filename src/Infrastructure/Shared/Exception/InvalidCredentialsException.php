<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Exception;

use App\Domain\Shared\Exception\Base\DomainExceptionInterface;
use App\Infrastructure\Shared\Exception\Base\RuntimeException;

class InvalidCredentialsException extends RuntimeException implements DomainExceptionInterface
{
    public function __construct(string $message = null)
    {
        parent::__construct($message);
    }
}
