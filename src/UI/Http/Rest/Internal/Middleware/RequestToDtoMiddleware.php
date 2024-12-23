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

        if (is_array($controller) && isset($controller[0]) && method_exists($controller[0], '__invoke')) {
            $request = $event->getRequest();

            $dtoClass = $controller[0]->getDtoClass();
            $dto = $this->mapRequestToDto($request, $dtoClass);

            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                throw new \InvalidArgumentException((string)$errors);
            }
//            $request->attributes->set('dto', $dto);
        }
    }

    private function mapRequestToDto(Request $request, string $dtoClass): object
    {
        $queryParams = $request->query->all();

        // Prepare an array of values for the DTO
        $dtoValues = [];

        foreach ($queryParams as $key => $value) {
            // Check if it is a nested array (e.g. filter[email] or filter[uuid])
            if (is_array($value)) {
                // If the value is an array, check each element.
                foreach ($value as $subKey => $subValue) {
                    $dtoValues[$subKey] = $subValue;
                }
            } else {
                // If the value is simple, add it to the array for the DTO
                $dtoValues[$key] = $value;
            }
        }

        // We use reflection to map query parameters to DTOs
        $dto = new $dtoClass();

        foreach ($dtoValues as $key => $value) {
            // If the DTO class has a corresponding property, set its value
            if (property_exists($dto, $key)) {
                $dto->$key = $value;
            }
        }

        return $dto;
    }
}
