<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Exception\Base;

use App\Domain\Shared\Exception\Base\BaseExceptionInterface;

class RuntimeException extends \RuntimeException implements BaseExceptionInterface
{
}
