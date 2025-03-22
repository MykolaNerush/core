CREATE TABLE user_roles_mapping
(
    `uuid`  BINARY(16) NOT NULL,
    user_id BINARY(16) NOT NULL,
    role_id BINARY(16) NOT NULL,
    INDEX   IDX_9D36F721A76ED395 (user_id),
    INDEX   IDX_9D36F72157698A6A (role_id),
    PRIMARY KEY (`uuid`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`;
INSERT INTO core_test.user_roles_mapping (uuid, user_id, role_id)
VALUES (UUID_TO_BIN('b3f76527-edbb-4d28-a126-8f7214f829c6'),
        UUID_TO_BIN('ef784869-54d1-4c87-b5a9-cc6ae4289b85'),
        UUID_TO_BIN('7eecabed-405c-4e79-83ba-87118c45fe55'));
