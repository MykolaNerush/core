<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Serializer;

use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Serializer\JsonApiSerializer as FractalJsonApiSerializer;

final class JsonApiSerializer extends FractalJsonApiSerializer
{
    /**
     * Serialize an item.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, array<string|mixed>>
     */
    public function item(?string $resourceKey, array $data): array
    {
        $id = $this->getIdFromData($data);

        $resource = [
            'data' => [
                'id' => "$id",
                'type' => $resourceKey,
                'attributes' => $data,
            ],
        ];

        unset($resource['data']['attributes']['id']);

        if (isset($resource['data']['attributes']['links'])) {
            $customLinks = $data['links'];
            unset($resource['data']['attributes']['links']);
        }

        if (isset($resource['data']['attributes']['meta'])) {
            $resource['data']['meta'] = $data['meta'];
            unset($resource['data']['attributes']['meta']);
        }

        if ($this->shouldIncludeLinks()) {
            $resource['data']['links'] = [
                'self' => "{$this->baseUrl}/$resourceKey/$id",
            ];
            if (isset($customLinks)) {
                $resource['data']['links'] = array_merge($customLinks, $resource['data']['links']);
            }
        }

        return $resource;
    }

    /**
     * Serialize the paginator.
     *
     * @return array<string, array<string, mixed>>
     */
    public function paginator(PaginatorInterface $paginator): array
    {
        $currentPage = $paginator->getCurrentPage();
        $lastPage = $paginator->getLastPage();

        $pagination = [
            'total' => $paginator->getTotal(),
            'count' => $paginator->getCount(),
            'perPage' => $paginator->getPerPage(),
            'currentPage' => $currentPage,
            'totalPages' => $lastPage,
        ];

        $pagination['links'] = [];

        $pagination['links']['self'] = $paginator->getUrl($currentPage);
        $pagination['links']['first'] = $paginator->getUrl(1);

        if ($currentPage > 1) {
            $pagination['links']['prev'] = $paginator->getUrl($currentPage - 1);
        }

        if ($currentPage < $lastPage) {
            $pagination['links']['next'] = $paginator->getUrl($currentPage + 1);
        }

        $pagination['links']['last'] = $paginator->getUrl($lastPage);

        return ['pagination' => $pagination];
    }
}
