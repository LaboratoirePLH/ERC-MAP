<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191028135957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE attestation_occasion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE attestation_materiel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE attestation_occasion ADD id INT DEFAULT NULL');
        $this->addSql("UPDATE attestation_occasion SET id = nextval('attestation_occasion_id_seq')");
        $this->addSql('ALTER TABLE attestation_occasion ALTER COLUMN id SET NOT NULL');
        $this->addSql('ALTER TABLE attestation_occasion ADD id_categorie_occasion INT DEFAULT NULL');
        $this->addSql('UPDATE attestation_occasion ao SET id_categorie_occasion = (SELECT o.categorie_occasion_id FROM occasion o WHERE o.id = ao.id_occasion)');
        $this->addSql('ALTER TABLE attestation_occasion DROP CONSTRAINT attestation_occasion_pkey');
        $this->addSql('ALTER TABLE attestation_occasion ALTER id_attestation DROP NOT NULL');
        $this->addSql('ALTER TABLE attestation_occasion ALTER id_occasion DROP NOT NULL');
        $this->addSql('ALTER TABLE attestation_occasion ADD CONSTRAINT FK_7BDC492831C32F20 FOREIGN KEY (id_categorie_occasion) REFERENCES categorie_occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7BDC492831C32F20 ON attestation_occasion (id_categorie_occasion)');
        $this->addSql('ALTER TABLE attestation_occasion ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE attestation_materiel ADD id INT DEFAULT NULL');
        $this->addSql("UPDATE attestation_materiel SET id = nextval('attestation_materiel_id_seq')");
        $this->addSql('ALTER TABLE attestation_materiel ALTER COLUMN id SET NOT NULL');
        $this->addSql('ALTER TABLE attestation_materiel ADD id_categorie_materiel INT DEFAULT NULL');
        $this->addSql('UPDATE attestation_materiel am SET id_categorie_materiel = (SELECT m.categorie_materiel_id FROM materiel m WHERE m.id = am.id_materiel)');
        $this->addSql('ALTER TABLE attestation_materiel DROP CONSTRAINT attestation_materiel_pkey');
        $this->addSql('ALTER TABLE attestation_materiel ALTER id_attestation DROP NOT NULL');
        $this->addSql('ALTER TABLE attestation_materiel ALTER id_materiel DROP NOT NULL');
        $this->addSql('ALTER TABLE attestation_materiel ADD CONSTRAINT FK_E968255CA3774354 FOREIGN KEY (id_categorie_materiel) REFERENCES categorie_materiel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E968255CA3774354 ON attestation_materiel (id_categorie_materiel)');
        $this->addSql('ALTER TABLE attestation_materiel ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE attestation_occasion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE attestation_materiel_id_seq CASCADE');
        $this->addSql('ALTER TABLE attestation_materiel DROP CONSTRAINT FK_E968255CA3774354');
        $this->addSql('DROP INDEX IDX_E968255CA3774354');
        $this->addSql('ALTER TABLE attestation_materiel DROP CONSTRAINT attestation_materiel_pkey');
        $this->addSql('ALTER TABLE attestation_materiel DROP id');
        $this->addSql('ALTER TABLE attestation_materiel DROP id_categorie_materiel');
        $this->addSql('ALTER TABLE attestation_materiel ALTER id_attestation SET NOT NULL');
        $this->addSql('ALTER TABLE attestation_materiel ALTER id_materiel SET NOT NULL');
        $this->addSql('ALTER TABLE attestation_materiel ADD PRIMARY KEY (id_attestation, id_materiel)');
        $this->addSql('ALTER TABLE attestation_occasion DROP CONSTRAINT FK_7BDC492831C32F20');
        $this->addSql('DROP INDEX IDX_7BDC492831C32F20');
        $this->addSql('ALTER TABLE attestation_occasion DROP CONSTRAINT attestation_occasion_pkey');
        $this->addSql('ALTER TABLE attestation_occasion DROP id');
        $this->addSql('ALTER TABLE attestation_occasion DROP id_categorie_occasion');
        $this->addSql('ALTER TABLE attestation_occasion ALTER id_attestation SET NOT NULL');
        $this->addSql('ALTER TABLE attestation_occasion ALTER id_occasion SET NOT NULL');
        $this->addSql('ALTER TABLE attestation_occasion ADD PRIMARY KEY (id_attestation, id_occasion)');
    }
}
