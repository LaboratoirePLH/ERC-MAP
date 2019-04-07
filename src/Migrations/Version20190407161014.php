<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190407161014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE attestation_occasion (id_attestation INT NOT NULL, id_occasion INT NOT NULL, PRIMARY KEY(id_attestation, id_occasion))');
        $this->addSql('CREATE INDEX IDX_7BDC49282A7C0BC8 ON attestation_occasion (id_attestation)');
        $this->addSql('CREATE INDEX IDX_7BDC4928DABD3070 ON attestation_occasion (id_occasion)');
        $this->addSql('ALTER TABLE attestation_occasion ADD CONSTRAINT FK_7BDC49282A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_occasion ADD CONSTRAINT FK_7BDC4928DABD3070 FOREIGN KEY (id_occasion) REFERENCES occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT fk_326ec63f31c32f20');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT fk_326ec63fdabd3070');
        $this->addSql('DROP INDEX idx_326ec63f31c32f20');
        $this->addSql('DROP INDEX idx_326ec63fdabd3070');
        $this->addSql('ALTER TABLE attestation DROP id_categorie_occasion');
        $this->addSql('ALTER TABLE attestation DROP id_occasion');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE attestation_occasion');
        $this->addSql('ALTER TABLE attestation ADD id_categorie_occasion INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attestation ADD id_occasion INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT fk_326ec63f31c32f20 FOREIGN KEY (id_categorie_occasion) REFERENCES categorie_occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT fk_326ec63fdabd3070 FOREIGN KEY (id_occasion) REFERENCES occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_326ec63f31c32f20 ON attestation (id_categorie_occasion)');
        $this->addSql('CREATE INDEX idx_326ec63fdabd3070 ON attestation (id_occasion)');
    }
}
