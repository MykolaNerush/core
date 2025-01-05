<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Shared\Response;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;

final class BaseJsonApiFormatter
{
    /**
     * @return array<string, mixed>
     */
    public static function one(Item $resource): array
    {
        return array_filter([
            'data' => self::model($resource),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function collection(Collection $collection): array
    {
        return $collection->data;
    }

    /**
     * @return array<string, mixed>
     */
    protected static function model(Item $resource): array
    {
        return [
            'id' => $resource->id,
            'type' => $resource->type,
            'attributes' => $resource->resource,
        ];
    }
}
