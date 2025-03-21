<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Domain\Core\Account\Entity\Account;
use App\Infrastructure\Core\Account\Repository\AccountRepository;
use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;
use Symfony\Component\HttpFoundation\Response;

class UpdateAccountByIdControllerTest extends BaseTestCase
{
    #[DataProvider('updateAccountsErrorProvider')]
    public function testUpdateAccountError($uuid, $params, $expectedResult): void
    {
        $client = static::createAuthClient();

        $client->request('POST', '/api/v1/internal/accounts/' . $uuid, $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function updateAccountsErrorProvider(): array
    {
        return [
            'Error, not valid fields.' => [
                '1fcd19a8-49af-4ffd-abc7-283f2cd05cd6',
                [
                    'accountName' => 'n',
                    'email' => 'wrongEmail',
                ],
                [
                    'status' => 'error',
                    'messages' => ['The name must be at least 2 characters long.'],
                    'code' => 400,
                ]
            ],
        ];
    }

    #[DataProvider('successUpdateAccountsProvider')]
    public function testSuccessUpdateAccount($uuid, $params): void
    {
        $client = static::createAuthClient();

        $client->request('POST', '/api/v1/internal/accounts/' . $uuid, $params);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $accountRepository = self::getContainer()->get(AccountRepository::class);
        /* @var ?Account $updatedAccount */
        $updatedAccount = $accountRepository->findOneBy(['accountName' => $params['accountName']]);
        $this->assertNotNull($updatedAccount);
        $this->assertEquals($params['accountName'], $updatedAccount->getAccountName());
    }

    public static function successUpdateAccountsProvider(): array
    {
        return [
            'Success update account' => [
                '1fcd19a8-49af-4ffd-abc7-283f2cd05cd6',
                [
                    'accountName' => 'FOR_UPDATE_ACCOUNTS_1',
                ],
            ],
        ];
    }
}