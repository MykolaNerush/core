<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Shared\Response;

use App\Application\Query\Shared\Collection;

abstract class BaseJsonApiFormatter
{
    /**
     * @return array<string, mixed>
     */
    abstract public static function collection(Collection $collection): array;
}
