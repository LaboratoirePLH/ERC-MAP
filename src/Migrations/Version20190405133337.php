<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190405133337 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE verrou_entite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE verrou_entite (id INT NOT NULL, user_creation_id INT NOT NULL, date_fin TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E87AB10F9DE46F0F ON verrou_entite (user_creation_id)');
        $this->addSql('ALTER TABLE verrou_entite ADD CONSTRAINT FK_E87AB10F9DE46F0F FOREIGN KEY (user_creation_id) REFERENCES chercheur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE element ADD verrou_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E391E25C1F6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_41405E391E25C1F6 ON element (verrou_id)');
        $this->addSql('ALTER TABLE attestation ADD verrou_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63F1E25C1F6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_326EC63F1E25C1F6 ON attestation (verrou_id)');
        $this->addSql('ALTER TABLE source ADD verrou_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F731E25C1F6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5F8A7F731E25C1F6 ON source (verrou_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E391E25C1F6');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT FK_326EC63F1E25C1F6');
        $this->addSql('ALTER TABLE source DROP CONSTRAINT FK_5F8A7F731E25C1F6');
        $this->addSql('DROP SEQUENCE verrou_entite_id_seq CASCADE');
        $this->addSql('DROP TABLE verrou_entite');
        $this->addSql('DROP INDEX IDX_41405E391E25C1F6');
        $this->addSql('ALTER TABLE element DROP verrou_id');
        $this->addSql('DROP INDEX IDX_326EC63F1E25C1F6');
        $this->addSql('ALTER TABLE attestation DROP verrou_id');
        $this->addSql('DROP INDEX IDX_5F8A7F731E25C1F6');
        $this->addSql('ALTER TABLE source DROP verrou_id');
    }
}
