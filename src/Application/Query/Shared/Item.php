<?php

declare(strict_types=1);

namespace App\Application\Query\Shared;

use Broadway\ReadModel\SerializableReadModel;

final class Item
{
    public string $id;

    public string $type;

    /**
     * @var array<string, string|mixed>
     */
    public array $resource;

    public SerializableReadModel $serializableReadModel;

    public function __construct(SerializableReadModel $serializableReadModel)
    {
        $this->id = $serializableReadModel->getId();
        $this->type = $this->type($serializableReadModel);
        $this->resource = $serializableReadModel->serialize();
        $this->serializableReadModel = $serializableReadModel;
    }

    private function type(SerializableReadModel $model): string
    {
        $path = explode('\\', \get_class($model));

        return lcfirst(array_pop($path));
    }
}
