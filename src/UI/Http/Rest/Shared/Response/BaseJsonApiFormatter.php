<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Shared\Response;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;

final class BaseJsonApiFormatter implements BaseJsonApiFormatterInterface
{
    public static function one(Item $resource): array
    {
        return [
            'status' => 'success',
            'data' => self::model($resource),
        ];
    }

    public static function collection(Collection $collection): array
    {
        $data = $collection->PaginatedData;
        return [
            'status' => 'success',
            'data' => $data->getData(),
            'meta' => $data->getMeta(),
            'links' => $data->getLinks(),
        ];
    }

    public static function model(Item $resource): array
    {
        return [
            'id' => $resource->id,
            'type' => $resource->type,
            'attributes' => $resource->resource,
        ];
    }
}
