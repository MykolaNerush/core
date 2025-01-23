<?php

declare(strict_types=1);

namespace Tests\App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Query\Shared\Collection;
use App\UI\Http\Rest\Internal\Controller\V1\User\GetUsersController;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetUsersControllerTest extends TestCase
{
    public function testInvokeReturnsSuccessfulResponse(): void
    {
        $usersData = [
            'data' => [
                [
                    'id' => 'd6cd6549-f1cf-4fa2-9fcc-34aced7cd4fe',
                    "type" => "users",
                    'attributes' => [
                        'uuid' => 'd6cd6549-f1cf-4fa2-9fcc-34aced7cd4fe',
                        'name' => 'John Doe',
                        'email' => 'john.doe@example.com',
                    ]
                ],
                [
                    'id' => 'd6cd6549-f1cf-4fa2-9fcc-34aced7cd4fg',
                    "type" => "users",
                    'attributes' => [
                        'uuid' => 'd6cd6549-f1cf-4fa2-9fcc-34aced7cd4fg',
                        'name' => 'Jane Doe',
                        'email' => 'jane.doe@example.com',
                    ]
                ],
            ]
        ];

        $collection = new Collection(
            page: 1,
            perPage: 10,
            total: 2,
            data: $usersData
        );

        $handledStamp = new HandledStamp($collection, 'handler');
        $envelope = new Envelope($collection, [$handledStamp]);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch')
            ->willReturn($envelope);

        $formatter = new BaseJsonApiFormatter();
        $mockRouter = $this->createMock(UrlGeneratorInterface::class);

        $controller = new GetUsersController($formatter, $mockRouter);

        $request = new Request();

        $response = $controller($request, $messageBus);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        $this->assertNotNull($responseData); // Переконатись, що відповідь не порожня
//        $this->assertArrayHasKey('data', $responseData);
        $this->assertSame('d6cd6549-f1cf-4fa2-9fcc-34aced7cd4fe', $responseData['data'][0]['id']);
        $this->assertSame('John Doe', $responseData['data'][0]['attributes']['name']);
        $this->assertSame('john.doe@example.com', $responseData['data'][0]['attributes']['email']);
        $this->assertSame('d6cd6549-f1cf-4fa2-9fcc-34aced7cd4fg', $responseData['data'][1]['id']);
        $this->assertSame('Jane Doe', $responseData['data'][1]['attributes']['name']);
        $this->assertSame('jane.doe@example.com', $responseData['data'][1]['attributes']['email']);
    }


    public function testInvokeThrowsRuntimeExceptionWhenHandlerNotFound(): void
    {
        $envelope = new Envelope(new \stdClass(), []);
        $mockFormatter = new BaseJsonApiFormatter();
        $mockRouter = $this->createMock(UrlGeneratorInterface::class);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch')
            ->willReturn($envelope);
        $controller = new GetUsersController($mockFormatter, $mockRouter);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No handler was found for this query or handler failed to execute.');

        $controller(new Request(), $messageBus);
    }
}
