<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314141027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE favorite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "like_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relation_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ressource_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE settings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE theme_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN category.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, creator_id INT NOT NULL, ressource_id INT NOT NULL, content TEXT NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C61220EA6 ON comment (creator_id)');
        $this->addSql('CREATE INDEX IDX_9474526CFC6CD52A ON comment (ressource_id)');
        $this->addSql('COMMENT ON COLUMN comment.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE favorite (id INT NOT NULL, user_favorite_id INT DEFAULT NULL, ressource_favorite_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_68C58ED930C8188 ON favorite (user_favorite_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9A3686E5E ON favorite (ressource_favorite_id)');
        $this->addSql('CREATE TABLE "like" (id INT NOT NULL, user_like_id INT DEFAULT NULL, ressource_like_id INT DEFAULT NULL, is_liked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC6340B3DD96E438 ON "like" (user_like_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B330A174D0 ON "like" (ressource_like_id)');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, ressource_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, file_size INT DEFAULT NULL, mimetype VARCHAR(255) DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A2CA10CFC6CD52A ON media (ressource_id)');
        $this->addSql('COMMENT ON COLUMN media.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN media.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, receiver_id INT NOT NULL, sender_id INT NOT NULL, resource_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5476CACD53EDB6 ON notification (receiver_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAF624B39D ON notification (sender_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA89329D25 ON notification (resource_id)');
        $this->addSql('COMMENT ON COLUMN notification.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE relation (id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, relation_type_id INT NOT NULL, is_accepted BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62894749F624B39D ON relation (sender_id)');
        $this->addSql('CREATE INDEX IDX_62894749CD53EDB6 ON relation (receiver_id)');
        $this->addSql('CREATE INDEX IDX_62894749DC379EE2 ON relation (relation_type_id)');
        $this->addSql('COMMENT ON COLUMN relation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN relation.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE relation_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN relation_type.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ressource (id INT NOT NULL, category_id INT NOT NULL, creator_id INT NOT NULL, description TEXT NOT NULL, is_valid BOOLEAN DEFAULT NULL, is_published BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_939F454412469DE2 ON ressource (category_id)');
        $this->addSql('CREATE INDEX IDX_939F454461220EA6 ON ressource (creator_id)');
        $this->addSql('COMMENT ON COLUMN ressource.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ressource_relation_type (ressource_id INT NOT NULL, relation_type_id INT NOT NULL, PRIMARY KEY(ressource_id, relation_type_id))');
        $this->addSql('CREATE INDEX IDX_32ADC4E0FC6CD52A ON ressource_relation_type (ressource_id)');
        $this->addSql('CREATE INDEX IDX_32ADC4E0DC379EE2 ON ressource_relation_type (relation_type_id)');
        $this->addSql('CREATE TABLE settings (id INT NOT NULL, user_id INT NOT NULL, theme_id INT NOT NULL, is_dark BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, allow_notifications BOOLEAN NOT NULL, use_device_mode BOOLEAN NOT NULL, language VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E545A0C5A76ED395 ON settings (user_id)');
        $this->addSql('CREATE INDEX IDX_E545A0C559027487 ON settings (theme_id)');
        $this->addSql('COMMENT ON COLUMN settings.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE theme (id INT NOT NULL, label VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, primary_color VARCHAR(255) NOT NULL, secondary_color VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, account_name VARCHAR(255) NOT NULL, birthday TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".birthday IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C61220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CFC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED930C8188 FOREIGN KEY (user_favorite_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A3686E5E FOREIGN KEY (ressource_favorite_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3DD96E438 FOREIGN KEY (user_like_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B330A174D0 FOREIGN KEY (ressource_like_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CFC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA89329D25 FOREIGN KEY (resource_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749F624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749DC379EE2 FOREIGN KEY (relation_type_id) REFERENCES relation_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454461220EA6 FOREIGN KEY (creator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ressource_relation_type ADD CONSTRAINT FK_32ADC4E0FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ressource_relation_type ADD CONSTRAINT FK_32ADC4E0DC379EE2 FOREIGN KEY (relation_type_id) REFERENCES relation_type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C559027487 FOREIGN KEY (theme_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE favorite_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "like_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE media_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE relation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE relation_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ressource_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE settings_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE theme_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C61220EA6');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CFC6CD52A');
        $this->addSql('ALTER TABLE favorite DROP CONSTRAINT FK_68C58ED930C8188');
        $this->addSql('ALTER TABLE favorite DROP CONSTRAINT FK_68C58ED9A3686E5E');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3DD96E438');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B330A174D0');
        $this->addSql('ALTER TABLE media DROP CONSTRAINT FK_6A2CA10CFC6CD52A');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CACD53EDB6');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CAF624B39D');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA89329D25');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749F624B39D');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749CD53EDB6');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749DC379EE2');
        $this->addSql('ALTER TABLE ressource DROP CONSTRAINT FK_939F454412469DE2');
        $this->addSql('ALTER TABLE ressource DROP CONSTRAINT FK_939F454461220EA6');
        $this->addSql('ALTER TABLE ressource_relation_type DROP CONSTRAINT FK_32ADC4E0FC6CD52A');
        $this->addSql('ALTER TABLE ressource_relation_type DROP CONSTRAINT FK_32ADC4E0DC379EE2');
        $this->addSql('ALTER TABLE settings DROP CONSTRAINT FK_E545A0C5A76ED395');
        $this->addSql('ALTER TABLE settings DROP CONSTRAINT FK_E545A0C559027487');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE "like"');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE relation_type');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE ressource_relation_type');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE "user"');
    }
}
