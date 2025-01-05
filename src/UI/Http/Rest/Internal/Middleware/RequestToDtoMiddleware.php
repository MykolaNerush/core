<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class RequestToDtoMiddleware
{
    public function __construct(
        private ValidatorInterface $validator,
    )
    {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller) && isset($controller[0]) && is_object($controller[0]) && method_exists($controller[0], '__invoke')) {
            $request = $event->getRequest();

            $controllerObject = $controller[0];
            if (property_exists($controllerObject, 'dtoClass') && is_string($controllerObject->dtoClass)) {
                $dtoClass = $controllerObject->dtoClass;
                $dto = $this->mapRequestToDto($request, $dtoClass);

                $errors = $this->validator->validate($dto);
                if (count($errors) > 0) {
                    throw new \InvalidArgumentException((string)$errors);
                }
//            $request->attributes->set('dto', $dto);
            } else {
                throw new \LogicException('The controller does not have a "dtoClass" property.');
            }
        }
    }

    private function mapRequestToDto(Request $request, string $dtoClass): object
    {
        $queryParams = $request->query->all();

        $contentType = $request->headers->get('Content-Type');
        $bodyParams = [];
        if (str_contains($contentType ?? '', 'application/json')) {
            $bodyParams = json_decode($request->getContent(), true) ?? [];
        } elseif ($request->request->count() > 0) {
            $bodyParams = $request->request->all();
        }
        $bodyParams = is_array($bodyParams) ? $bodyParams : [];
        // query and body params
        $allParams = array_merge($queryParams, $bodyParams);

        $dtoValues = [];

        foreach ($allParams as $key => $value) {
            // Check for nested arrays (e.g., filter[email] or filter[uuid])
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $dtoValues[$subKey] = $subValue;
                }
            } else {
                $dtoValues[$key] = $value;
            }
        }

        $dto = new $dtoClass();

        foreach ($dtoValues as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }
        }

        return $dto;
    }
}
