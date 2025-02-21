<?php

declare(strict_types=1);

namespace App\Tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Command\User\Update\UpdateUserCommand;
use App\UI\Http\Rest\Internal\Controller\V1\User\UpdateUserByIdController;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class UpdateUserByIdControllerTest extends WebTestCase
{
    public function testInvokeWithValidRequest(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $request = new Request([], ['name' => 'John Doe', 'email' => 'johndoe@example.com', 'password' => 'securePassword']);

        $updateUserCommand = new UpdateUserCommand(
            currentUuid: Uuid::fromString($uuid),
            name: 'John Doe',
            email: 'johndoe@example.com',
            password: 'securePassword'
        );

        $handledStamp = new HandledStamp('success', 'handler_alias');
        $envelope = new Envelope($updateUserCommand, [$handledStamp]);

        $mockMessageBus = $this->createMock(MessageBusInterface::class);
        $mockMessageBus->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo($updateUserCommand))
            ->willReturn($envelope);

        $controller = new UpdateUserByIdController();
        $response = $controller->__invoke($uuid, $request, $mockMessageBus);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testInvokeWithMissingHandler(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $request = new Request([], ['name' => 'John Doe', 'email' => 'johndoe@example.com', 'password' => 'securePassword']);

        $updateUserCommand = new UpdateUserCommand(
            currentUuid: Uuid::fromString($uuid),
            name: 'John Doe',
            email: 'johndoe@example.com',
            password: 'securePassword'
        );

        $envelope = new Envelope($updateUserCommand);

        $mockMessageBus = $this->createMock(MessageBusInterface::class);
        $mockMessageBus->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo($updateUserCommand))
            ->willReturn($envelope);

        $controller = new UpdateUserByIdController();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No handler was found for this query or handler failed to execute.');

        $controller->__invoke($uuid, $request, $mockMessageBus);
    }

    public function testInvokeWithInvalidUuid(): void
    {
        $invalidUuid = 'invalid_uuid';
        $request = new Request([], ['name' => 'John Doe', 'email' => 'johndoe@example.com', 'password' => 'securePassword']);
        $mockMessageBus = $this->createMock(MessageBusInterface::class);

        $controller = new UpdateUserByIdController();

        $this->expectException(\InvalidArgumentException::class);

        $controller->__invoke($invalidUuid, $request, $mockMessageBus);
    }
}