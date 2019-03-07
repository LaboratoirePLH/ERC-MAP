<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190307101501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE agentivite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE agent_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE activite_agent_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pratique_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE categorie_occasion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE statu_affiche_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE materiel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE genre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE nature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE attestation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE categorie_materiel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE occasion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE etat_fiche_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE agentivite (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE agent (id INT NOT NULL, id_attestation INT DEFAULT NULL, localisation_decouverte_id INT DEFAULT NULL, designation TEXT DEFAULT NULL, commentaire_fr VARCHAR(255) DEFAULT NULL, commentaire_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_268B9C9D2A7C0BC8 ON agent (id_attestation)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_268B9C9D343700E0 ON agent (localisation_decouverte_id)');
        $this->addSql('CREATE TABLE agent_statut (id_agent INT NOT NULL, id_statut SMALLINT NOT NULL, PRIMARY KEY(id_agent, id_statut))');
        $this->addSql('CREATE INDEX IDX_BB83A799C80EDDAD ON agent_statut (id_agent)');
        $this->addSql('CREATE INDEX IDX_BB83A799C3534552 ON agent_statut (id_statut)');
        $this->addSql('CREATE TABLE agent_nature (id_agent INT NOT NULL, id_nature SMALLINT NOT NULL, PRIMARY KEY(id_agent, id_nature))');
        $this->addSql('CREATE INDEX IDX_EF3FD581C80EDDAD ON agent_nature (id_agent)');
        $this->addSql('CREATE INDEX IDX_EF3FD58197EF374A ON agent_nature (id_nature)');
        $this->addSql('CREATE TABLE agent_genre (id_agent INT NOT NULL, id_genre SMALLINT NOT NULL, PRIMARY KEY(id_agent, id_genre))');
        $this->addSql('CREATE INDEX IDX_BAFE1B80C80EDDAD ON agent_genre (id_agent)');
        $this->addSql('CREATE INDEX IDX_BAFE1B806DD572C8 ON agent_genre (id_genre)');
        $this->addSql('CREATE TABLE agent_activite (id_agent INT NOT NULL, id_activite SMALLINT NOT NULL, PRIMARY KEY(id_agent, id_activite))');
        $this->addSql('CREATE INDEX IDX_8EA44C81C80EDDAD ON agent_activite (id_agent)');
        $this->addSql('CREATE INDEX IDX_8EA44C81E8AEB980 ON agent_activite (id_activite)');
        $this->addSql('CREATE TABLE agent_agentivite (id_agent INT NOT NULL, id_agentivite SMALLINT NOT NULL, PRIMARY KEY(id_agent, id_agentivite))');
        $this->addSql('CREATE INDEX IDX_AC21DF1CC80EDDAD ON agent_agentivite (id_agent)');
        $this->addSql('CREATE INDEX IDX_AC21DF1C78ED8EC4 ON agent_agentivite (id_agentivite)');
        $this->addSql('CREATE TABLE activite_agent (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pratique (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE categorie_occasion (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE statu_affiche (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE materiel (id SMALLINT NOT NULL, categorie_materiel_id SMALLINT DEFAULT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_18D2B091C9762CA6 ON materiel (categorie_materiel_id)');
        $this->addSql('CREATE TABLE genre (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE nature (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE attestation (id INT NOT NULL, id_source INT DEFAULT NULL, id_etat_fiche SMALLINT DEFAULT NULL, datation_id INT DEFAULT NULL, localisation_decouverte_id INT DEFAULT NULL, user_creation_id INT NOT NULL, user_edition_id INT NOT NULL, passage VARCHAR(255) DEFAULT NULL, extrait_sans_restitution TEXT DEFAULT NULL, extrait_avec_restitution TEXT DEFAULT NULL, translitteration TEXT DEFAULT NULL, fiabilite_attestation SMALLINT DEFAULT NULL, prose BOOLEAN DEFAULT \'true\', poesie BOOLEAN DEFAULT \'false\', est_datee BOOLEAN DEFAULT NULL, date_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_modification TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, version INT NOT NULL, traduire_fr BOOLEAN DEFAULT NULL, traduire_en BOOLEAN DEFAULT NULL, commentaire_fr VARCHAR(255) DEFAULT NULL, commentaire_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_326EC63F79BDCA9E ON attestation (id_source)');
        $this->addSql('CREATE INDEX IDX_326EC63FAF0E6016 ON attestation (id_etat_fiche)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_326EC63F6AE6DFC0 ON attestation (datation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_326EC63F343700E0 ON attestation (localisation_decouverte_id)');
        $this->addSql('CREATE INDEX IDX_326EC63F9DE46F0F ON attestation (user_creation_id)');
        $this->addSql('CREATE INDEX IDX_326EC63FD34FDCB2 ON attestation (user_edition_id)');
        $this->addSql('CREATE TABLE attestation_pratique (id_attestation INT NOT NULL, id_pratique SMALLINT NOT NULL, PRIMARY KEY(id_attestation, id_pratique))');
        $this->addSql('CREATE INDEX IDX_F048224C2A7C0BC8 ON attestation_pratique (id_attestation)');
        $this->addSql('CREATE INDEX IDX_F048224C51295B14 ON attestation_pratique (id_pratique)');
        $this->addSql('CREATE TABLE attestation_categorie_occasion (id_attestation INT NOT NULL, id_categorie_occasion SMALLINT NOT NULL, PRIMARY KEY(id_attestation, id_categorie_occasion))');
        $this->addSql('CREATE INDEX IDX_3A9DE4ED2A7C0BC8 ON attestation_categorie_occasion (id_attestation)');
        $this->addSql('CREATE INDEX IDX_3A9DE4ED31C32F20 ON attestation_categorie_occasion (id_categorie_occasion)');
        $this->addSql('CREATE TABLE attestation_occasion (id_attestation INT NOT NULL, id_occasion SMALLINT NOT NULL, PRIMARY KEY(id_attestation, id_occasion))');
        $this->addSql('CREATE INDEX IDX_7BDC49282A7C0BC8 ON attestation_occasion (id_attestation)');
        $this->addSql('CREATE INDEX IDX_7BDC4928DABD3070 ON attestation_occasion (id_occasion)');
        $this->addSql('CREATE TABLE categorie_materiel (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE occasion (id SMALLINT NOT NULL, categorie_occasion_id SMALLINT DEFAULT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A66DCE59FCABF86 ON occasion (categorie_occasion_id)');
        $this->addSql('CREATE TABLE attestation_materiel (id_attestation INT NOT NULL, id_materiel SMALLINT NOT NULL, quantite SMALLINT DEFAULT NULL, PRIMARY KEY(id_attestation, id_materiel))');
        $this->addSql('CREATE INDEX IDX_E968255C2A7C0BC8 ON attestation_materiel (id_attestation)');
        $this->addSql('CREATE INDEX IDX_E968255C48095C04 ON attestation_materiel (id_materiel)');
        $this->addSql('CREATE TABLE etat_fiche (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D2A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D343700E0 FOREIGN KEY (localisation_decouverte_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_statut ADD CONSTRAINT FK_BB83A799C80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_statut ADD CONSTRAINT FK_BB83A799C3534552 FOREIGN KEY (id_statut) REFERENCES statu_affiche (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_nature ADD CONSTRAINT FK_EF3FD581C80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_nature ADD CONSTRAINT FK_EF3FD58197EF374A FOREIGN KEY (id_nature) REFERENCES nature (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_genre ADD CONSTRAINT FK_BAFE1B80C80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_genre ADD CONSTRAINT FK_BAFE1B806DD572C8 FOREIGN KEY (id_genre) REFERENCES genre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_activite ADD CONSTRAINT FK_8EA44C81C80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_activite ADD CONSTRAINT FK_8EA44C81E8AEB980 FOREIGN KEY (id_activite) REFERENCES activite_agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_agentivite ADD CONSTRAINT FK_AC21DF1CC80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agent_agentivite ADD CONSTRAINT FK_AC21DF1C78ED8EC4 FOREIGN KEY (id_agentivite) REFERENCES agentivite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B091C9762CA6 FOREIGN KEY (categorie_materiel_id) REFERENCES categorie_materiel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63F79BDCA9E FOREIGN KEY (id_source) REFERENCES source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63FAF0E6016 FOREIGN KEY (id_etat_fiche) REFERENCES etat_fiche (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63F6AE6DFC0 FOREIGN KEY (datation_id) REFERENCES datation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63F343700E0 FOREIGN KEY (localisation_decouverte_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63F9DE46F0F FOREIGN KEY (user_creation_id) REFERENCES chercheur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63FD34FDCB2 FOREIGN KEY (user_edition_id) REFERENCES chercheur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_pratique ADD CONSTRAINT FK_F048224C2A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_pratique ADD CONSTRAINT FK_F048224C51295B14 FOREIGN KEY (id_pratique) REFERENCES pratique (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_categorie_occasion ADD CONSTRAINT FK_3A9DE4ED2A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_categorie_occasion ADD CONSTRAINT FK_3A9DE4ED31C32F20 FOREIGN KEY (id_categorie_occasion) REFERENCES categorie_occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_occasion ADD CONSTRAINT FK_7BDC49282A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_occasion ADD CONSTRAINT FK_7BDC4928DABD3070 FOREIGN KEY (id_occasion) REFERENCES occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE occasion ADD CONSTRAINT FK_8A66DCE59FCABF86 FOREIGN KEY (categorie_occasion_id) REFERENCES categorie_occasion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_materiel ADD CONSTRAINT FK_E968255C2A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation_materiel ADD CONSTRAINT FK_E968255C48095C04 FOREIGN KEY (id_materiel) REFERENCES materiel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source ALTER id_projet DROP NOT NULL');
        $this->addSql('ALTER INDEX idx_5f8a7f73c18272 RENAME TO IDX_5F8A7F7376222944');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE agent_agentivite DROP CONSTRAINT FK_AC21DF1C78ED8EC4');
        $this->addSql('ALTER TABLE agent_statut DROP CONSTRAINT FK_BB83A799C80EDDAD');
        $this->addSql('ALTER TABLE agent_nature DROP CONSTRAINT FK_EF3FD581C80EDDAD');
        $this->addSql('ALTER TABLE agent_genre DROP CONSTRAINT FK_BAFE1B80C80EDDAD');
        $this->addSql('ALTER TABLE agent_activite DROP CONSTRAINT FK_8EA44C81C80EDDAD');
        $this->addSql('ALTER TABLE agent_agentivite DROP CONSTRAINT FK_AC21DF1CC80EDDAD');
        $this->addSql('ALTER TABLE agent_activite DROP CONSTRAINT FK_8EA44C81E8AEB980');
        $this->addSql('ALTER TABLE attestation_pratique DROP CONSTRAINT FK_F048224C51295B14');
        $this->addSql('ALTER TABLE attestation_categorie_occasion DROP CONSTRAINT FK_3A9DE4ED31C32F20');
        $this->addSql('ALTER TABLE occasion DROP CONSTRAINT FK_8A66DCE59FCABF86');
        $this->addSql('ALTER TABLE agent_statut DROP CONSTRAINT FK_BB83A799C3534552');
        $this->addSql('ALTER TABLE attestation_materiel DROP CONSTRAINT FK_E968255C48095C04');
        $this->addSql('ALTER TABLE agent_genre DROP CONSTRAINT FK_BAFE1B806DD572C8');
        $this->addSql('ALTER TABLE agent_nature DROP CONSTRAINT FK_EF3FD58197EF374A');
        $this->addSql('ALTER TABLE agent DROP CONSTRAINT FK_268B9C9D2A7C0BC8');
        $this->addSql('ALTER TABLE attestation_pratique DROP CONSTRAINT FK_F048224C2A7C0BC8');
        $this->addSql('ALTER TABLE attestation_categorie_occasion DROP CONSTRAINT FK_3A9DE4ED2A7C0BC8');
        $this->addSql('ALTER TABLE attestation_occasion DROP CONSTRAINT FK_7BDC49282A7C0BC8');
        $this->addSql('ALTER TABLE attestation_materiel DROP CONSTRAINT FK_E968255C2A7C0BC8');
        $this->addSql('ALTER TABLE materiel DROP CONSTRAINT FK_18D2B091C9762CA6');
        $this->addSql('ALTER TABLE attestation_occasion DROP CONSTRAINT FK_7BDC4928DABD3070');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT FK_326EC63FAF0E6016');
        $this->addSql('DROP SEQUENCE agentivite_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE agent_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE activite_agent_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pratique_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE categorie_occasion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE statu_affiche_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE materiel_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE genre_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE nature_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE attestation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE categorie_materiel_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE occasion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE etat_fiche_id_seq CASCADE');
        $this->addSql('DROP TABLE agentivite');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE agent_statut');
        $this->addSql('DROP TABLE agent_nature');
        $this->addSql('DROP TABLE agent_genre');
        $this->addSql('DROP TABLE agent_activite');
        $this->addSql('DROP TABLE agent_agentivite');
        $this->addSql('DROP TABLE activite_agent');
        $this->addSql('DROP TABLE pratique');
        $this->addSql('DROP TABLE categorie_occasion');
        $this->addSql('DROP TABLE statu_affiche');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE nature');
        $this->addSql('DROP TABLE attestation');
        $this->addSql('DROP TABLE attestation_pratique');
        $this->addSql('DROP TABLE attestation_categorie_occasion');
        $this->addSql('DROP TABLE attestation_occasion');
        $this->addSql('DROP TABLE categorie_materiel');
        $this->addSql('DROP TABLE occasion');
        $this->addSql('DROP TABLE attestation_materiel');
        $this->addSql('DROP TABLE etat_fiche');
        $this->addSql('ALTER TABLE source ALTER id_projet SET NOT NULL');
        $this->addSql('ALTER INDEX idx_5f8a7f7376222944 RENAME TO idx_5f8a7f73c18272');
    }
}
