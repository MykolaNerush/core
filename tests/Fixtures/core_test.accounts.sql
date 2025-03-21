CREATE TABLE `core_test`.`accounts`
(
    `uuid`         binary(16) NOT NULL,
    `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `balance` double NOT NULL,
    `created_at`   datetime                                NOT NULL,
    `updated_at`   datetime DEFAULT NULL,
    `deleted_at`   datetime DEFAULT NULL,
    `status`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_uuid`    binary(16) DEFAULT NULL,
    PRIMARY KEY (`uuid`),
    KEY            `IDX_CAC89EACABFE1C6F` (`user_uuid`),
    CONSTRAINT `FK_CAC89EACABFE1C6F` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `core_test`.`accounts`
(`uuid`, `account_name`, `balance`, `created_at`, `updated_at`, `deleted_at`, `status`, `user_uuid`)
VALUES (UUID_TO_BIN('1fcd19a8-49af-4ffd-abc7-000000000000'),
        'FOR_DELETE',
        1000.0,
        '2025-01-05 16:44:16',
        NULL,
        NULL,
        'active',
        0x4BDE84646FC349049095B89305EFA532),
       (UUID_TO_BIN('1fcd19a8-49af-4ffd-abc7-000000000001'),
        'FOR_FIND_BY_UD',
        2000.0,
        '2025-01-05 16:44:16',
        NULL,
        NULL,
        'active',
        0xE5010159F3614AB4B5A24557144E3F1E),
       (UUID_TO_BIN('1fcd19a8-49af-4ffd-abc7-e45b3143fb25'),
        'FOR_LIST_ACCOUNTS',
        1500.0,
        '2025-01-05 16:44:16',
        NULL,
        NULL,
        'active',
        0x4C6A78C7773C4D2B939902F6B35AA09A),
       (UUID_TO_BIN('1fcd19a8-49af-4ffd-abc7-283f2cd05cd6'),
        'FOR_UPDATE_ACCOUNTS',
        1500.0,
        '2025-01-05 16:44:16',
        NULL,
        NULL,
        'active',
        0x4C6A78C7773C4D2B939902F6B35AA09A);