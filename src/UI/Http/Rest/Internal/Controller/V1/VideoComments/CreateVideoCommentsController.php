<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\VideoComments;

use App\Application\Command\VideoComments\Create\CreateVideoCommentsCommand;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\VideoComment\Entity\VideoComment;
use App\Domain\Shared\Security\ResourceVoter;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use App\UI\Http\Rest\Internal\DTO\VideoComments\CreateVideoCommentsRequest;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'Video comments')]
final class CreateVideoCommentsController extends CommandController
{
    public string $dtoClass = CreateVideoCommentsRequest::class;

    #[OA\Post(
        summary: 'Create video comments',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(response: Response::HTTP_CREATED, description: 'video comments created successfully'),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
            new OA\Response(response: Response::HTTP_CONFLICT, description: 'Conflict'),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error')
        ]
    )]
    #[OA\Parameter(
        name: 'video_uuid',
        description: 'video uuid',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Parameter(
        name: 'comment',
        description: 'Comment',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    public function __invoke(
        Request                $request,
        MessageBusInterface    $messageBus,
        EntityManagerInterface $entityManager,
        UserRepositoryInterface  $userRepository, //todo remove after add auth user
    ): JsonResponse
    {
        $videoUuid = $request->get('video_uuid');
        $video = $entityManager->getRepository(Video::class)->findOneBy(['uuid' => $videoUuid]);

        $this->denyAccessUnlessGranted(ResourceVoter::VIEW, ['repo' => VideoComment::class, 'uuid' => $videoUuid]);
        if (!$video) {
            throw new NotFoundHttpException('Video not found');
        }

        $userUuid = 'e6b1dc09-ebe2-4139-9b9d-77122aa7c3c9';
        $uuid = Uuid::fromString($userUuid);
        //todo add get auth user
        /* @var User $user*/
        $user = $userRepository->getByUuid($uuid);

        $comment = $request->get('comment');

        $command = new CreateVideoCommentsCommand(
            video: $video,
            user: $user,
            comment: $comment,
        );

        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);

        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }

        $videoCommentsId = $handledStamp->getResult();

        return new JsonResponse(['id' => $videoCommentsId], Response::HTTP_CREATED);
    }
}
