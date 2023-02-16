<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209140127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media ADD file_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD file_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE media DROP link');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE media ADD link TEXT NOT NULL');
        $this->addSql('ALTER TABLE media DROP file_path');
        $this->addSql('ALTER TABLE media DROP file_size');
        $this->addSql('ALTER TABLE media DROP updated_at');
    }
}
