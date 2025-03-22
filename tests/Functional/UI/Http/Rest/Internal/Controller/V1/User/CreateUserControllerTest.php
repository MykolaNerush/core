<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use App\Domain\Core\User\Entity\User;
use App\Infrastructure\Core\User\Repository\UserRepository;
use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;

class CreateUserControllerTest extends BaseTestCase
{
    #[DataProvider('createUsersErrorProvider')]
    public function testCreateUsersError($params, $expectedResult): void
    {
        $client = static::createAuthClient();

        $client->request('POST', '/api/v1/internal/users', $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function createUsersErrorProvider(): array
    {
        return [
//            'Error, required fields are missing.' => [
//                [
//                ],
//                [
//                    'status' => 'error',
//                    'messages' =>
//                        array(
//                            0 => 'The name field is required.',
//                            1 => 'The email field is required.',
//                            2 => 'The password field is required.',
//                        ),
//                    'code' => 400,
//                ]
//            ],
            'Error, Email already exists.' => [
                [
                    'name' => 'testNameErrorEmail',
                    'email' => 'test@gmail.com',
                    'password' => 'password',
                ],
                [
                    'status' => 'error',
                    'messages' => ['Email already exists.'],
                    'code' => 400,
                ]
            ],
        ];

    }


    #[DataProvider('successCreateUsersProvider')]
    public function testSuccessCreateUsers($params): void
    {
        $client = static::createAuthClient();

        $client->request('POST', '/api/v1/internal/users', $params);
        json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $userRepository = self::getContainer()->get(UserRepository::class);
        /* @var ?User $createdUser */
        $createdUser = $userRepository->findOneBy(['name' => $params['name'], 'email' => $params['email']]);
        $this->assertNotNull($createdUser);
        $this->assertEquals($params['name'], $createdUser->getName());
        $this->assertEquals($params['email'], $createdUser->getEmail());
        $this->assertEquals($params['status'], $createdUser->getStatus()->label());
    }

    public static function successCreateUsersProvider(): array
    {
        return [
            'Success, create user' => [
                [
                    'name' => 'testNameSuccess',
                    'email' => 'test_createw@gmail.com',
                    'password' => 'password_create',
                    'status' => 'New',
                ],
            ],
        ];

    }
}