<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250315162839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authentication_log (
            id INT AUTO_INCREMENT NOT NULL,
            user_id BINARY(16) DEFAULT NULL,
            email VARCHAR(255) DEFAULT NULL,
            ip VARCHAR(45) NOT NULL,
            status VARCHAR(20) NOT NULL,
            reason VARCHAR(255) DEFAULT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY(id),
            INDEX idx_auth_log_ip (ip),
            INDEX idx_auth_log_created_at (created_at),
            INDEX idx_auth_log_user_id (user_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE authentication_log');
    }
}
