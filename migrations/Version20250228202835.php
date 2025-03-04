<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228202835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE video_comments (uuid BINARY(16) NOT NULL, comment VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, video_uuid BINARY(16) NOT NULL, user_uuid BINARY(16) NOT NULL, INDEX IDX_F71CA496D6E80D7A (video_uuid), INDEX IDX_F71CA496ABFE1C6F (user_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE video_comments ADD CONSTRAINT FK_F71CA496D6E80D7A FOREIGN KEY (video_uuid) REFERENCES videos (uuid)');
        $this->addSql('ALTER TABLE video_comments ADD CONSTRAINT FK_F71CA496ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES users (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video_comments DROP FOREIGN KEY FK_F71CA496D6E80D7A');
        $this->addSql('ALTER TABLE video_comments DROP FOREIGN KEY FK_F71CA496ABFE1C6F');
        $this->addSql('DROP TABLE video_comments');
    }
}
