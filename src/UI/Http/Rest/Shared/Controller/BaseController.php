<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Shared\Controller;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatterInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class BaseController
{
    public function __construct(
        protected BaseJsonApiFormatterInterface $formatter,
        protected UrlGeneratorInterface         $router,
    )
    {
    }

    /**
     * @template T of array<int, array<string, mixed>>
     * @param Collection<T> $collection
     */
    protected function jsonCollection(Collection $collection): JsonResponse
    {
        return new JsonResponse($this->formatter::collection($collection));
    }

    /**
     * @template T of array<string, mixed>
     * @param Item<T> $resource
     */
    protected function json(Item $resource): JsonResponse
    {
        return new JsonResponse($this->formatter::one($resource));
    }


    /**
     * @param array<string, mixed> $params
     */
    protected function route(
        string $name,
        array  $params = [],
        int    $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string
    {
        return $this->router->generate($name, $params, $referenceType);
    }

    protected function routeWithPage(int $page, Request $request): string
    {
        $route = $request->attributes->get('_route');
        $route = is_string($route) ? $route : '';
        $inputParams = $request->attributes->get('_route_params');
        $inputParams = is_array($inputParams) ? $inputParams : [];
        $newParams = array_merge($inputParams, $request->query->all());
        $newParams['page'] = $page;
        return $this->route($route, $newParams, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    protected function routeWithPageAsCallable(Request $request): \Closure
    {
        return function (int $page) use ($request) {
            return $this->routeWithPage($page, $request);
        };
    }

    /**
     * Get instance of UuidInterface from string or null.
     */
    protected function getUuidOrNull(?string $param): ?UuidInterface
    {
        if (null === $param) {
            return null;
        }
        try {
            return Uuid::fromString($param);
        } catch (InvalidUuidStringException) {
            return null;
        }
    }
}
