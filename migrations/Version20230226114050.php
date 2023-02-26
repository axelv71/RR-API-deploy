<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226114050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ressource_relation_type (ressource_id INT NOT NULL, relation_type_id INT NOT NULL, PRIMARY KEY(ressource_id, relation_type_id))');
        $this->addSql('CREATE INDEX IDX_32ADC4E0FC6CD52A ON ressource_relation_type (ressource_id)');
        $this->addSql('CREATE INDEX IDX_32ADC4E0DC379EE2 ON ressource_relation_type (relation_type_id)');
        $this->addSql('ALTER TABLE ressource_relation_type ADD CONSTRAINT FK_32ADC4E0FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ressource_relation_type ADD CONSTRAINT FK_32ADC4E0DC379EE2 FOREIGN KEY (relation_type_id) REFERENCES relation_type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ressource_relation_type DROP CONSTRAINT FK_32ADC4E0FC6CD52A');
        $this->addSql('ALTER TABLE ressource_relation_type DROP CONSTRAINT FK_32ADC4E0DC379EE2');
        $this->addSql('DROP TABLE ressource_relation_type');
    }
}
