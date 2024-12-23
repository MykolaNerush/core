<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\User;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[OA\Tag(name: 'User')]
final class CreateUserController
{
    public function __invoke(Request $request): JsonResponse
    {
        //todo implementation
    }
}
