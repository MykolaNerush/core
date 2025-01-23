<?php

namespace Tests\Unit\App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Command\User\Create\CreateUserCommand;
use App\UI\Http\Rest\Internal\Controller\V1\User\CreateUserController;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class CreateUserControllerTest extends TestCase
{
    private Request $request;
    private BaseJsonApiFormatter $mockFormatter;
    private UrlGeneratorInterface $mockRouter;
    private MessageBusInterface $mockMessageBus;
    protected function setUp(): void
    {
        $this->mockFormatter = new BaseJsonApiFormatter();
        $this->mockRouter = $this->createMock(UrlGeneratorInterface::class);
        $this->mockMessageBus = $this->createMock(MessageBusInterface::class);
        $this->request = new Request(
            query: [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
            ]
        );
    }

    public function testCreateReturnsProperResponseOnSuccess(): void
    {
        $envelope = new Envelope(
            new CreateUserCommand(Uuid::uuid4(), 'Test User', 'test@example.com', 'password123'),
            [new HandledStamp(true, 'handler_name')]
        );
        $this->mockMessageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(CreateUserCommand::class))
            ->willReturn($envelope);

        $controller = new CreateUserController($this->mockFormatter, $this->mockRouter);
        $response = $controller->__invoke($this->request, $this->mockMessageBus);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertNotEmpty($responseData['id']);
        $this->assertTrue(Uuid::isValid($responseData['id']));
    }

    public function testCreateThrowsExceptionWhenNoHandlerStamp(): void
    {
        $this->mockMessageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(CreateUserCommand::class))
            ->willReturn(new Envelope(new CreateUserCommand(Uuid::uuid4(), 'Test User', 'test@example.com', 'password123')));

        $controller = new CreateUserController($this->mockFormatter, $this->mockRouter);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No handler was found for this query or handler failed to execute.');
        $controller->__invoke($this->request, $this->mockMessageBus);
    }
}