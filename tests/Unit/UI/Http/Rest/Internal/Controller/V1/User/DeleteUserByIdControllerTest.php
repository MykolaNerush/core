<?php

namespace Tests\Unit\App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Command\User\Delete\DeleteUserCommand;
use App\UI\Http\Rest\Internal\Controller\V1\User\DeleteUserByIdController;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class DeleteUserByIdControllerTest extends TestCase
{
    private DeleteUserByIdController $controller;
    private MessageBusInterface $mockedMessageBus;

    protected function setUp(): void
    {
        $this->mockedMessageBus = $this->createMock(MessageBusInterface::class);
        $this->controller = new DeleteUserByIdController();
    }

    public function testDeleteSuccess(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $handledStamp = new HandledStamp(true, 'handlerName');
        $envelope = new Envelope(new DeleteUserCommand(Uuid::fromString($uuid)), [$handledStamp]);

        $this->mockedMessageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(DeleteUserCommand::class))
            ->willReturn($envelope);

        $response = ($this->controller)($uuid, $this->mockedMessageBus);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('{}', $response->getContent());
    }

    public function testDeleteThrowsExceptionWhenNoHandler(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $emptyEnvelope = new Envelope(new DeleteUserCommand(Uuid::fromString($uuid)));

        $this->mockedMessageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(DeleteUserCommand::class))
            ->willReturn($emptyEnvelope);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No handler was found for this query or handler failed to execute.');

        ($this->controller)($uuid, $this->mockedMessageBus);
    }
}