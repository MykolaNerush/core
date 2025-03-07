<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305114443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_roles (role VARCHAR(50) NOT NULL, PRIMARY KEY(role)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user_roles_mapping (user_id BINARY(16) NOT NULL, role VARCHAR(50) NOT NULL, INDEX IDX_9D36F721A76ED395 (user_id), INDEX IDX_9D36F72157698A6A (role), PRIMARY KEY(user_id, role)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_roles_mapping ADD CONSTRAINT FK_9D36F721A76ED395 FOREIGN KEY (user_id) REFERENCES users (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_roles_mapping ADD CONSTRAINT FK_9D36F72157698A6A FOREIGN KEY (role) REFERENCES user_roles (role) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video_comments CHANGE comment comment LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_roles_mapping DROP FOREIGN KEY FK_9D36F721A76ED395');
        $this->addSql('ALTER TABLE user_roles_mapping DROP FOREIGN KEY FK_9D36F72157698A6A');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP TABLE user_roles_mapping');
        $this->addSql('ALTER TABLE video_comments CHANGE comment comment VARCHAR(255) NOT NULL');
    }
}
