<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190307133458 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE attestation_categorie_occasion');
        $this->addSql('DROP TABLE attestation_occasion');
        $this->addSql('ALTER TABLE attestation ADD id_categorie_occasion SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE attestation ADD id_occasion SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63F31C32F20 FOREIGN KEY (id_categorie_occasion) REFERENCES categorie_occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63FDABD3070 FOREIGN KEY (id_occasion) REFERENCES occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_326EC63F31C32F20 ON attestation (id_categorie_occasion)');
        $this->addSql('CREATE INDEX IDX_326EC63FDABD3070 ON attestation (id_occasion)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE attestation_categorie_occasion (id_attestation INT NOT NULL, id_categorie_occasion SMALLINT NOT NULL, PRIMARY KEY(id_attestation, id_categorie_occasion))');
        $this->addSql('CREATE INDEX idx_3a9de4ed31c32f20 ON attestation_categorie_occasion (id_categorie_occasion)');
        $this->addSql('CREATE INDEX idx_3a9de4ed2a7c0bc8 ON attestation_categorie_occasion (id_attestation)');
        $this->addSql('CREATE TABLE attestation_occasion (id_attestation INT NOT NULL, id_occasion SMALLINT NOT NULL, PRIMARY KEY(id_attestation, id_occasion))');
        $this->addSql('CREATE INDEX idx_7bdc49282a7c0bc8 ON attestation_occasion (id_attestation)');
        $this->addSql('CREATE INDEX idx_7bdc4928dabd3070 ON attestation_occasion (id_occasion)');
        $this->addSql('ALTER TABLE attestation_categorie_occasion ADD CONSTRAINT fk_3a9de4ed2a7c0bc8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_categorie_occasion ADD CONSTRAINT fk_3a9de4ed31c32f20 FOREIGN KEY (id_categorie_occasion) REFERENCES categorie_occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_occasion ADD CONSTRAINT fk_7bdc49282a7c0bc8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_occasion ADD CONSTRAINT fk_7bdc4928dabd3070 FOREIGN KEY (id_occasion) REFERENCES occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT FK_326EC63F31C32F20');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT FK_326EC63FDABD3070');
        $this->addSql('DROP INDEX IDX_326EC63F31C32F20');
        $this->addSql('DROP INDEX IDX_326EC63FDABD3070');
        $this->addSql('ALTER TABLE attestation DROP id_categorie_occasion');
        $this->addSql('ALTER TABLE attestation DROP id_occasion');
    }
}
