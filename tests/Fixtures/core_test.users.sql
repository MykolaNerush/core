CREATE TABLE `core_test`.`users`
(
    `uuid`       binary(16) NOT NULL,
    `name`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email`      varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password`   varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `status`     varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` datetime                                NOT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`uuid`),
    UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `core_test`.`users`
(`uuid`, `name`, `email`, `password`, `status`, `created_at`, `updated_at`, `deleted_at`)
VALUES (UUID_TO_BIN('1fcd19a8-49af-44fd-abc7-560f8b41581e'),
        'name1',
        'test@gmail.com12',
        '1111',
        'new',
        '2025-01-08 17:47:30',
        NULL,
        NULL),
       (UUID_TO_BIN('4bde8464-6fc3-4904-9095-b89305efa532'),
        'Test',
        'test@gmail.com',
        'test',
        'active',
        '2025-01-05 16:44:16',
        NULL,
        NULL),
       (UUID_TO_BIN('4c6a78c7-773c-4d2b-9399-02f6b35aa09a'),
        'Jane Doe',
        'jane@example.com',
        'password',
        'active',
        '2025-01-05 16:44:16',
        NULL,
        NULL),
       (UUID_TO_BIN('cb0a1dc6-8c80-4e43-9ca0-223d13a627e5'),
        'name1',
        'test@gmail.com1',
        '1111',
        'new',
        '2025-01-08 17:47:01',
        NULL,
        NULL),
       (UUID_TO_BIN('e5010159-f361-4ab4-b5a2-4557144e3f1e'),
        'Alice Smith',
        'alice@example.com',
        'password',
        'active',
        '2025-01-05 16:44:16',
        NULL,
        NULL),
       (UUID_TO_BIN('e5010159-f361-4ab4-b5a2-4557144e3f11'),
        'Test_update',
        'test_update@example.com',
        'test_update_password',
        'active',
        '2025-01-05 16:44:16',
        NULL,
        NULL),
       (UUID_TO_BIN('e5010159-f361-4ab4-b5a2-4557144e3f12'),
        'Test_delete',
        'test_delete@example.com',
        'test_delete_password',
        'active',
        '2025-01-05 16:44:16',
        NULL,
        NULL),
       (UUID_TO_BIN('724afee0-d001-47e5-a9d4-29a3f19b81b8'),
        'Test_create_account',
        'test_create_account@example.com',
        'test_create_account_password',
        'active',
        '2025-01-05 16:44:16',
        NULL,
        NULL);
