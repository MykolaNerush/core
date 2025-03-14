<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Exception;

use App\Domain\Shared\Exception\Base\DomainExceptionInterface;
use App\Infrastructure\Shared\Exception\Base\RuntimeException;

class InvalidPasswordException extends RuntimeException implements DomainExceptionInterface
{
}
