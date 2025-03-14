<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Domain\Core\Account\Entity\Account;
use App\Infrastructure\Core\Account\Repository\AccountRepository;
use App\Tests\Functional\BaseTestCase;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;
use Symfony\Component\HttpFoundation\Response;

class CreateAccountControllerTest extends BaseTestCase
{
    #[DataProvider('createAccountErrorProvider')]
    public function testCreateAccountError($params, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/accounts', $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function createAccountErrorProvider(): array
    {
        return [
            'Error, required fields are missing.' => [
                [
                ],
                [
                    'status' => 'error',
                    'messages' => ['The accountName field is required.'],
                    'code' => 400,
                ]
            ],
        ];

    }

    #[DataProvider('successCreateAccountProvider')]
    public function testSuccessCreateAccounts($params, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/accounts', $params);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $accountRepository = self::getContainer()->get(AccountRepository::class);
        /* @var ?Account $createdAccount */
        $createdAccount = $accountRepository->findOneBy(['accountName' => $params['accountName']]);
        $this->assertNotNull($createdAccount);
        $this->assertEquals($params['accountName'], $createdAccount->getAccountName());
        $this->assertEquals($expectedResult['status'], $createdAccount->getStatus()->label());
    }

    public static function successCreateAccountProvider(): array
    {
        return [
            'Success, create account' => [
                [
                    'accountName' => 'testNameSuccess',
                ],
                [
                    'status' => 'New',
                ],
            ],
        ];

    }
}