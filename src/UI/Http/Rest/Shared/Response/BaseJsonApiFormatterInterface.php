<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Shared\Response;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;

interface BaseJsonApiFormatterInterface
{
    /**
     * @template T of array<string, mixed>
     * @param Item<T> $resource
     * @return T
     */
    public static function one(Item $resource): array;

    /**
     * @template T of array<int, array<string, mixed>>
     * @param Collection<T> $collection
     * @return T
     */
    public static function collection(Collection $collection): array;

    /**
     * @template T of array<string, mixed>
     * @param Item<T> $resource
     * @return T
     */
    public static function model(Item $resource): array;
}
