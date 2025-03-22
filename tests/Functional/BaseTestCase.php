<?php

namespace App\Tests\Functional;

use App\Infrastructure\Core\Auth\Cache\AuthTokenCache;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        $dsn = 'mysql:host=20.5.0.3;dbname=core_test;charset=utf8mb4';
        $username = 'root';
        $password = 'test';

        try {
            $pdo = new \PDO($dsn, $username, $password, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);

            $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

            $clearDbSql = file_get_contents(__DIR__ . '/../Fixtures/Shared/clear_db.sql');
            $pdo->exec($clearDbSql);

            $dumps = [
                'core_test.users.sql',
                'core_test.accounts.sql',
                'core_test.videos.sql',
                'core_test.user_roles.sql',
                'core_test.user_roles_mapping.sql',
            ];

            foreach ($dumps as $dump) {
                $sql = file_get_contents(__DIR__ . '/../Fixtures/' . $dump);
                $pdo->exec($sql);
            }

            $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
        } catch (\PDOException $e) {
            throw new \RuntimeException('Failed to set up the database: ' . $e->getMessage(), 0, $e);
        }
    }

    protected static function createAuthClient(string $username = 'auth@gmail.com', string $password = 'test'): KernelBrowser
    {
        $client = static::createClient();
        $container = static::getContainer();
        $authTokenCache = $container->get(AuthTokenCache::class);

        try {
            $token = $authTokenCache->get($username, $password);
        } catch (\Exception) {
            $token = null;
        }

        if ($token === null) {
            $client->request(
                'POST',
                '/api/v1/internal/signin',
                [
                    'email' => $username,
                    'password' => $password,
                ]
            );
            $data = json_decode($client->getResponse()->getContent(), true);
            $token = $data['token'];

            try {
                $authTokenCache->set($username, $password, $token);
            } catch (\Exception) {
                // Ignore cache errors
            }
        }

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));

        return $client;
    }
}