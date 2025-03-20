<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320153905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accounts (uuid BINARY(16) NOT NULL, account_name VARCHAR(255) NOT NULL, balance INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, user_uuid BINARY(16) DEFAULT NULL, INDEX IDX_CAC89EACABFE1C6F (user_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user_roles (uuid BINARY(16) NOT NULL, role VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_USER_ROLE_ROLE (role), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user_roles_mapping (uuid BINARY(16) NOT NULL, user_id BINARY(16) DEFAULT NULL, role_id BINARY(16) DEFAULT NULL, INDEX IDX_9D36F721A76ED395 (user_id), INDEX IDX_9D36F721D60322AC (role_id), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user_videos (role VARCHAR(255) NOT NULL, user_uuid BINARY(16) NOT NULL, video_uuid BINARY(16) NOT NULL, INDEX IDX_4CF87F0FABFE1C6F (user_uuid), INDEX IDX_4CF87F0FD6E80D7A (video_uuid), PRIMARY KEY(user_uuid, video_uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE users (uuid BINARY(16) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, is_email_confirmed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE video_comments (uuid BINARY(16) NOT NULL, comment LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, video_uuid BINARY(16) NOT NULL, user_uuid BINARY(16) NOT NULL, INDEX IDX_F71CA496D6E80D7A (video_uuid), INDEX IDX_F71CA496ABFE1C6F (user_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE videos (uuid BINARY(16) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, thumbnail_path VARCHAR(255) DEFAULT NULL, duration INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EACABFE1C6F FOREIGN KEY (user_uuid) REFERENCES users (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_roles_mapping ADD CONSTRAINT FK_9D36F721A76ED395 FOREIGN KEY (user_id) REFERENCES users (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_roles_mapping ADD CONSTRAINT FK_9D36F721D60322AC FOREIGN KEY (role_id) REFERENCES user_roles (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_videos ADD CONSTRAINT FK_4CF87F0FABFE1C6F FOREIGN KEY (user_uuid) REFERENCES users (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_videos ADD CONSTRAINT FK_4CF87F0FD6E80D7A FOREIGN KEY (video_uuid) REFERENCES videos (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_comments ADD CONSTRAINT FK_F71CA496D6E80D7A FOREIGN KEY (video_uuid) REFERENCES videos (uuid)');
        $this->addSql('ALTER TABLE video_comments ADD CONSTRAINT FK_F71CA496ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES users (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EACABFE1C6F');
        $this->addSql('ALTER TABLE user_roles_mapping DROP FOREIGN KEY FK_9D36F721A76ED395');
        $this->addSql('ALTER TABLE user_roles_mapping DROP FOREIGN KEY FK_9D36F721D60322AC');
        $this->addSql('ALTER TABLE user_videos DROP FOREIGN KEY FK_4CF87F0FABFE1C6F');
        $this->addSql('ALTER TABLE user_videos DROP FOREIGN KEY FK_4CF87F0FD6E80D7A');
        $this->addSql('ALTER TABLE video_comments DROP FOREIGN KEY FK_F71CA496D6E80D7A');
        $this->addSql('ALTER TABLE video_comments DROP FOREIGN KEY FK_F71CA496ABFE1C6F');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP TABLE user_roles_mapping');
        $this->addSql('DROP TABLE user_videos');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE video_comments');
        $this->addSql('DROP TABLE videos');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
