<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Response;

use App\Application\Query\FullCollectionInterface;
use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatter;

final class JsonApiFormatter extends BaseJsonApiFormatter
{
    /**
     * @return Item[]
     */
    public static function collection(Collection $collection): array
    {
        return $collection->data;
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public static function fullCollection(FullCollectionInterface $collection): array
    {
        return ['data' => $collection->getData()];
    }
}
