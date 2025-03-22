<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use App\Domain\Core\User\Entity\User;
use App\Infrastructure\Core\User\Repository\UserRepository;
use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserByIdControllerTest extends BaseTestCase
{
    #[DataProvider('updateUsersErrorProvider')]
    public function testUpdateUserError($uuid, $params, $expectedResult): void
    {
        $client = static::createAuthClient();

        $client->request('POST', '/api/v1/internal/users/' . $uuid, $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function updateUsersErrorProvider(): array
    {
        return [
            'Error, not valid fields.' => [
                'e5010159-f361-4ab4-b5a2-4557144e3f11',
                [
                    'name' => 'n',
                    'email' => 'wrongEmail',
                ],
                [
                    'status' => 'error',
                    'messages' =>
                        array(
                            0 => 'The name must be at least 2 characters long.',
                            1 => 'The email format is invalid.',
                        ),
                    'code' => 400,
                ]
            ],
        ];
    }

    #[DataProvider('successUpdateUsersProvider')]
    public function testSuccessUpdateUser($uuid, $params): void
    {
        $client = static::createAuthClient();

        $client->request('POST', '/api/v1/internal/users/' . $uuid, $params);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $userRepository = self::getContainer()->get(UserRepository::class);
        /* @var ?User $updatedUser */
        $updatedUser = $userRepository->findOneBy(['name' => $params['name']]);
        $this->assertNotNull($updatedUser);
        $this->assertEquals($params['name'], $updatedUser->getName());
    }

    public static function successUpdateUsersProvider(): array
    {
        return [
            'Success update user' => [
                'e5010159-f361-4ab4-b5a2-4557144e3f11',
                [
                    'name' => 'Test_update_1',
                ],
            ],
        ];
    }
}