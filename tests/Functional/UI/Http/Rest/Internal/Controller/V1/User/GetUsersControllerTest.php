<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetUsersControllerTest extends WebTestCase
{
    #[DataProvider('additionProvider')]
    public function testGetUsers($expectedResult, $filter, $expectedCode): void
    {
        $client = static::createClient();

        $client->request('GET', '/users');
        $client->request('GET', '/api/v1/internal/users?' . $filter);
        $actual = json_decode($client->getResponse()->getContent(), true)['data'];
        $this->assertEquals($expectedResult, $actual);
        $this->assertResponseStatusCodeSame($expectedCode);
    }

    public static function additionProvider(): \Generator
    {
        yield [
            [], // expected
            'page=111&perPage=11&order=DESC&sort=name', // filters
            200,
            'Empty data, wrong page' // description
        ];
//todo add
//1. Тест на успішний запит (200 OK) - перевірити статус і респонс
//2. Тест на відсутність результатів (200 OK, порожня колекція) - перевірити статус і респонс, користувачів немає
//3. Тест на неправильні параметри сортування (400 Bad Request)
//4. Тест на неправильний формат UUID (400 Bad Request)
//5. Тест на відсутність авторизації (401 Unauthorized)
//6. Тест на неправильні параметри запиту (400 Bad Request)
//7. Тест на обробку помилок обробника запиту (500 Internal Server Error)
//8. Тест на фільтрацію за email (200 OK)
//9. Тест на пагінацію (200 OK)


    }
}