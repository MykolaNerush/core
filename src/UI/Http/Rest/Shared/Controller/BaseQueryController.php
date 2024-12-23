<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Shared\Controller;

use App\Application\Query\Shared\Collection;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class BaseQueryController extends BaseController
{
    public function __construct(
        protected BaseJsonApiFormatter  $formatter,
        protected UrlGeneratorInterface $router
    )
    {
    }

    protected function jsonCollection(Collection $collection): JsonResponse
    {
        return new JsonResponse($this->formatter::collection($collection));
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
        $inputParams = $request->attributes->get('_route_params');
        $newParams = array_merge($inputParams, $request->query->all());
        $newParams['page'] = $page;

        return $this->route($route, $newParams, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    protected function routeWithPageAsCallable(Request $request): callable
    {
        return function (int $page) use ($request) {
            return $this->routeWithPage($page, $request);
        };
    }
}
