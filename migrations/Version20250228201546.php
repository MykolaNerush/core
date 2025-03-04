<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228201546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_videos (role VARCHAR(255) NOT NULL, user_uuid BINARY(16) NOT NULL, video_uuid BINARY(16) NOT NULL, INDEX IDX_4CF87F0FABFE1C6F (user_uuid), INDEX IDX_4CF87F0FD6E80D7A (video_uuid), PRIMARY KEY(user_uuid, video_uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_videos ADD CONSTRAINT FK_4CF87F0FABFE1C6F FOREIGN KEY (user_uuid) REFERENCES users (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_videos ADD CONSTRAINT FK_4CF87F0FD6E80D7A FOREIGN KEY (video_uuid) REFERENCES videos (uuid) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_videos DROP FOREIGN KEY FK_4CF87F0FABFE1C6F');
        $this->addSql('ALTER TABLE user_videos DROP FOREIGN KEY FK_4CF87F0FD6E80D7A');
        $this->addSql('DROP TABLE user_videos');
    }
}
