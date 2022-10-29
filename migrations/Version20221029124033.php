<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221029124033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE favorite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "like_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE favorite (id INT NOT NULL, user_favorite_id INT DEFAULT NULL, ressource_favorite_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_68C58ED930C8188 ON favorite (user_favorite_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9A3686E5E ON favorite (ressource_favorite_id)');
        $this->addSql('CREATE TABLE "like" (id INT NOT NULL, user_like_id INT DEFAULT NULL, ressource_like_id INT DEFAULT NULL, is_liked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC6340B3DD96E438 ON "like" (user_like_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B330A174D0 ON "like" (ressource_like_id)');
        $this->addSql('CREATE TABLE relation (id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, relation_type_id INT NOT NULL, is_accepted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62894749F624B39D ON relation (sender_id)');
        $this->addSql('CREATE INDEX IDX_62894749CD53EDB6 ON relation (receiver_id)');
        $this->addSql('CREATE INDEX IDX_62894749DC379EE2 ON relation (relation_type_id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED930C8188 FOREIGN KEY (user_favorite_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A3686E5E FOREIGN KEY (ressource_favorite_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3DD96E438 FOREIGN KEY (user_like_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B330A174D0 FOREIGN KEY (ressource_like_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749F624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749DC379EE2 FOREIGN KEY (relation_type_id) REFERENCES relation_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ALTER role_name SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE favorite_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "like_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE relation_id_seq CASCADE');
        $this->addSql('ALTER TABLE favorite DROP CONSTRAINT FK_68C58ED930C8188');
        $this->addSql('ALTER TABLE favorite DROP CONSTRAINT FK_68C58ED9A3686E5E');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3DD96E438');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B330A174D0');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749F624B39D');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749CD53EDB6');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749DC379EE2');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE "like"');
        $this->addSql('DROP TABLE relation');
        $this->addSql('ALTER TABLE "user" ALTER role_name DROP NOT NULL');
    }
}
