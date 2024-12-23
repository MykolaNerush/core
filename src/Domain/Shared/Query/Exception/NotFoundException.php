<?php

namespace App\Domain\Shared\Query\Exception;

use App\Domain\Shared\Exception\Base\DomainExceptionInterface;
use App\Infrastructure\Shared\Exception\Base\RuntimeException;

class NotFoundException extends RuntimeException implements DomainExceptionInterface
{
    public function __construct(string $resourceName = null)
    {
        parent::__construct(sprintf('%s not found', $resourceName ?? 'Resource'));
    }
}
