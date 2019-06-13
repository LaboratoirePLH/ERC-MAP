<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607151401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE traduction_attestation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE traduction_attestation (id INT NOT NULL, id_attestation INT DEFAULT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_108CF5302A7C0BC8 ON traduction_attestation (id_attestation)');
        $this->addSql('ALTER TABLE traduction_attestation ADD CONSTRAINT FK_108CF5302A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation DROP extrait_sans_restitution');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE traduction_attestation_id_seq CASCADE');
        $this->addSql('DROP TABLE traduction_attestation');
        $this->addSql('ALTER TABLE attestation ADD extrait_sans_restitution TEXT DEFAULT NULL');
    }
}
