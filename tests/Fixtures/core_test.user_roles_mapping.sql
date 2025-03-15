CREATE TABLE user_roles_mapping
(
    user_id BINARY(16) NOT NULL,
    role    VARCHAR(50) NOT NULL,
    INDEX   IDX_9D36F721A76ED395 (user_id),
    INDEX   IDX_9D36F72157698A6A (role),
    PRIMARY KEY (user_id, role)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`

-- INSERT INTO `core_test`.`user_roles_mapping`
--     (uuid, title, description, status)
-- VALUES (UUID_TO_BIN('51ab6d16-21d1-438c-877f-310e7a3180e7'),
--         'name_FOR_DELETE_error',
--         'desc_for_delete_error',
--         'draft'),
-- ;
