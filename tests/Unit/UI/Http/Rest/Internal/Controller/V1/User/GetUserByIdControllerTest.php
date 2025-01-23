<?php

namespace Tests\App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Query\Shared\Item;
use App\UI\Http\Rest\Internal\Controller\V1\User\GetUserByIdController;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatter;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatterInterface;
use Broadway\ReadModel\SerializableReadModel;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetUserByIdControllerTest extends TestCase
{
    public function testInvokeReturnsSuccessfulResponse(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $serializableReadModelMock = $this->createMock(SerializableReadModel::class);
        $serializableReadModelMock->method('serialize')->willReturn([
            'id' => $uuid,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
        $serializableReadModelMock->method('getId')->willReturn($uuid);
        $user = new Item($serializableReadModelMock);
        $handledStamp = new HandledStamp($user, 'handler');
        $envelope = new Envelope($user, [$handledStamp]);
        $formatter = new BaseJsonApiFormatter();

        $mockRouter = $this->createMock(UrlGeneratorInterface::class);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch')
            ->willReturn($envelope);
        $controller = new GetUserByIdController($formatter, $mockRouter);
        $response = $controller($uuid, $messageBus);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);

        $this->assertSame($uuid, $responseData['data']['id']);
        $this->assertSame($uuid, $responseData['data']['attributes']['id']);
        $this->assertSame('John Doe', $responseData['data']['attributes']['name']);
        $this->assertSame('john.doe@example.com', $responseData['data']['attributes']['email']);
    }



    public function testInvokeThrowsRuntimeExceptionWhenHandlerNotFound(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $envelope = new Envelope(new \stdClass(), []);
        $mockFormatter = new BaseJsonApiFormatter();
        $mockRouter = $this->createMock(UrlGeneratorInterface::class);
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())
            ->method('dispatch')
            ->willReturn($envelope);

        $controller = new GetUserByIdController($mockFormatter, $mockRouter);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No handler was found for this query or handler failed to execute.');

        $controller($uuid, $messageBus);
    }
}