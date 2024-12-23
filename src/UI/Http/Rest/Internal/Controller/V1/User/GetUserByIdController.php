<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\User;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;

#[OA\Tag(name: 'User')]
final class GetUserByIdController
{
    public function __invoke(string $id): JsonResponse
    {
        //todo implementation
    }
}
