<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320163003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounts DROP INDEX IDX_CAC89EACABFE1C6F, ADD UNIQUE INDEX UNIQ_CAC89EACABFE1C6F (user_uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounts DROP INDEX UNIQ_CAC89EACABFE1C6F, ADD INDEX IDX_CAC89EACABFE1C6F (user_uuid)');
    }
}
