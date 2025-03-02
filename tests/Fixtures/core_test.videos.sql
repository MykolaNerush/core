CREATE TABLE `videos`
(
    `uuid`           binary(16) NOT NULL,
    `title`          varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description`    varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `file_path`      varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `duration`       int                                     NOT NULL,
    `status`         varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at`     datetime                                NOT NULL,
    `updated_at`     datetime                                DEFAULT NULL,
    `deleted_at`     datetime                                DEFAULT NULL,
    PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `core_test`.`videos`
    (uuid, title, description, status)
VALUES (UUID_TO_BIN('51ab6d16-21d1-438c-877f-310e7a3180e7'),
        'name_FOR_DELETE_error',
        'desc_for_delete_error',
        'draft'),
       (UUID_TO_BIN('8027f5f2-330f-4ea3-abcd-aeb4e1d3ea0e'),
        'name_FOR_DELETE_success',
        'desc_for_delete_success',
        'draft'),
       (UUID_TO_BIN('8027f5f2-330f-4ea3-abcd-aeb4e1d3ea01'),
        'title_get_success',
        'desc_get_success',
        'draft'),
       (UUID_TO_BIN('8027f5f2-330f-4ea3-abcd-aeb4e1d3ea02'),
        'title_get_list_success',
        'desc_get_list_success',
        'draft'),
       (UUID_TO_BIN('8027f5f2-330f-4ea3-abcd-aeb4e1d3ea03'),
        'title_update_success',
        'desc_update_success',
        'draft')
;
