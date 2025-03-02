<?php

namespace App\Tests\Functional;

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

}