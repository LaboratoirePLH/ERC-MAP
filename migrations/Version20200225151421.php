<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200225151421 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE lien_attestation (id_attestation1 INT NOT NULL, id_attestation2 INT NOT NULL, PRIMARY KEY(id_attestation1, id_attestation2))');
        $this->addSql('CREATE INDEX IDX_ADF9C2921649D93E ON lien_attestation (id_attestation1)');
        $this->addSql('CREATE INDEX IDX_ADF9C2928F408884 ON lien_attestation (id_attestation2)');
        $this->addSql('ALTER TABLE lien_attestation ADD CONSTRAINT FK_ADF9C2921649D93E FOREIGN KEY (id_attestation1) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lien_attestation ADD CONSTRAINT FK_ADF9C2928F408884 FOREIGN KEY (id_attestation2) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE lien_attestation');
    }
}
