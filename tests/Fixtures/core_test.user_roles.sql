CREATE TABLE `core_test`.`user_roles`
(
    `uuid` binary(16) NOT NULL,
    `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`uuid`),
    UNIQUE KEY `UNIQ_USER_ROLE_ROLE` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `core_test`.`user_roles`
    (uuid, `role`)
VALUES (UUID_TO_BIN('7eecabed-405c-4e79-83ba-87118c45fe55'), 'ROLE_ADMIN');