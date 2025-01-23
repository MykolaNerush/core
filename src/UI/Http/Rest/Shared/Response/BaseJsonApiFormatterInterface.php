<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Shared\Response;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;

interface BaseJsonApiFormatterInterface
{
    public static function one(Item $resource): array;

    public static function collection(Collection $collection): array;
    public static function model(Item $resource): array;
}
