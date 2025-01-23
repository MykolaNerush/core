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
VALUES (0x392CAD8F8FB6466D82E4A534BA5BFE69,
        'Main Account John',
        1000.0,
        '2025-01-05 16:44:16',
        NULL,
        NULL,
        'active',
        0x4BDE84646FC349049095B89305EFA532),
       (0x55975A06F5E745878C622FACBA7D535B,
        'Main Account Alice',
        2000.0,
        '2025-01-05 16:44:16',
        NULL,
        NULL,
        'active',
        0xE5010159F3614AB4B5A24557144E3F1E),
       (0xDDA18BA0C0E94269BF915382C8777A2A,
        'Main Account Jane',
        1500.0,
        '2025-01-05 16:44:16',
        NULL,
        NULL,
        'active',
        0x4C6A78C7773C4D2B939902F6B35AA09A);