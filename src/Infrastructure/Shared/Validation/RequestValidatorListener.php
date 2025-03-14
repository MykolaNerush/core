<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Validation;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

readonly class RequestValidatorListener
{
    public function __construct(private ValidatorInterface $validator, private SerializerInterface $serializer)
    {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller) && isset($controller[0]) && method_exists($controller[0], '__invoke')) {
            $request = $event->getRequest();

            if (property_exists($controller[0], 'dtoClass') && is_string($controller[0]->dtoClass)) {
                $dtoClass = $controller[0]->dtoClass;
                $dto = $this->validateRequest($request, $dtoClass);

                if ($dto instanceof JsonResponse) {
                    $event->setController(fn() => $dto);
                    return;
                }

                $request->attributes->set('dto', $dto);
            }
        }
    }

    private function validateRequest(Request $request, string $dtoClass): object
    {

        $data = array_merge(
            $request->query->all(),
            $request->request->all(),
            json_decode($request->getContent(), true) ?? []
        );
        $dto = $this->serializer->deserialize(json_encode($data), $dtoClass, 'json', ['disable_type_enforcement' => true]);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse([
                'status' => 'error',
                'messages' => $errorMessages,
                'code' => 400,
            ], 400);
        }

        return $dto;
    }
}
