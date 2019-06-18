<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190613100810 extends AbstractMigration
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
        $this->addSql('CREATE TABLE source_type_source (id_source INT NOT NULL, id_type_source INT NOT NULL, PRIMARY KEY(id_source, id_type_source))');
        $this->addSql('CREATE INDEX IDX_402244EB79BDCA9E ON source_type_source (id_source)');
        $this->addSql('CREATE INDEX IDX_402244EB644FD9BC ON source_type_source (id_type_source)');
        $this->addSql('CREATE TABLE traduction_attestation (id INT NOT NULL, id_attestation INT DEFAULT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_108CF5302A7C0BC8 ON traduction_attestation (id_attestation)');
        $this->addSql('ALTER TABLE source_type_source ADD CONSTRAINT FK_402244EB79BDCA9E FOREIGN KEY (id_source) REFERENCES source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_type_source ADD CONSTRAINT FK_402244EB644FD9BC FOREIGN KEY (id_type_source) REFERENCES type_source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE traduction_attestation ADD CONSTRAINT FK_108CF5302A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation DROP extrait_sans_restitution');
        $this->addSql('ALTER TABLE biblio ADD verrou_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biblio ADD CONSTRAINT FK_D90CBB251E25C1F6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D90CBB251E25C1F6 ON biblio (verrou_id)');
        $this->addSql('ALTER TABLE element_biblio DROP commentaire_fr');
        $this->addSql('ALTER TABLE element_biblio DROP commentaire_en');
        $this->addSql('ALTER TABLE source DROP CONSTRAINT fk_5f8a7f7321fa5d71');
        $this->addSql('DROP INDEX idx_5f8a7f7321fa5d71');
        $this->addSql('ALTER TABLE source DROP type_source_id');
        $this->addSql('ALTER TABLE source_biblio DROP commentaire_fr');
        $this->addSql('ALTER TABLE source_biblio DROP commentaire_en');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE traduction_attestation_id_seq CASCADE');
        $this->addSql('DROP TABLE source_type_source');
        $this->addSql('DROP TABLE traduction_attestation');
        $this->addSql('ALTER TABLE attestation ADD extrait_sans_restitution TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE source ADD type_source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT fk_5f8a7f7321fa5d71 FOREIGN KEY (type_source_id) REFERENCES type_source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5f8a7f7321fa5d71 ON source (type_source_id)');
        $this->addSql('ALTER TABLE biblio DROP CONSTRAINT FK_D90CBB251E25C1F6');
        $this->addSql('DROP INDEX IDX_D90CBB251E25C1F6');
        $this->addSql('ALTER TABLE biblio DROP verrou_id');
        $this->addSql('ALTER TABLE element_biblio ADD commentaire_fr TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE element_biblio ADD commentaire_en TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE source_biblio ADD commentaire_fr TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE source_biblio ADD commentaire_en TEXT DEFAULT NULL');
    }
}
