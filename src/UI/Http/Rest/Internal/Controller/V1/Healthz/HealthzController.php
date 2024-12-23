<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Healthz;

use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HealthzController
{
    #[OA\Get(
        summary: "Perform an API health check",
        tags: ['Health'],
        responses: [
            new OA\Response(response: 200, description: "Healthy"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 500, description: "Not healthy")
        ],
        deprecated: false
    )]
    #[Security(name: 'Bearer')]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse();
    }
}
