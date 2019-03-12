<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190312171507 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE element_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE nombre_element_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE nature_element_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE genre_element_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE traduction_element_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE categorie_element_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE element (id INT NOT NULL, id_nature_element INT DEFAULT NULL, localisation_id INT DEFAULT NULL, etat_absolu VARCHAR(255) DEFAULT NULL, etat_morphologique VARCHAR(255) DEFAULT NULL, beta_code VARCHAR(255) DEFAULT NULL, est_localisee BOOLEAN DEFAULT NULL, traduire_fr BOOLEAN DEFAULT NULL, traduire_en BOOLEAN DEFAULT NULL, commentaire_fr VARCHAR(255) DEFAULT NULL, commentaire_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_41405E3964A5A8EA ON element (id_nature_element)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_41405E39C68BE09C ON element (localisation_id)');
        $this->addSql('CREATE TABLE elements_theonymes (id_parent INT NOT NULL, id_enfant INT NOT NULL, PRIMARY KEY(id_parent, id_enfant))');
        $this->addSql('CREATE INDEX IDX_A4D66CFA1BB9D5A2 ON elements_theonymes (id_parent)');
        $this->addSql('CREATE INDEX IDX_A4D66CFA1280B94F ON elements_theonymes (id_enfant)');
        $this->addSql('CREATE TABLE nombre_element (id INT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE nature_element (id INT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE contient_element (id_attestation INT NOT NULL, id_element INT NOT NULL, id_categorie_element INT DEFAULT NULL, id_genre_element INT DEFAULT NULL, id_nombre_element INT DEFAULT NULL, position_element SMALLINT NOT NULL, suffixe BOOLEAN DEFAULT NULL, extrait_sans_restitution TEXT DEFAULT NULL, extrait_avec_restitution TEXT DEFAULT NULL, PRIMARY KEY(id_attestation, id_element))');
        $this->addSql('CREATE INDEX IDX_71BC92312A7C0BC8 ON contient_element (id_attestation)');
        $this->addSql('CREATE INDEX IDX_71BC92319FDDF749 ON contient_element (id_element)');
        $this->addSql('CREATE INDEX IDX_71BC9231EFF4988C ON contient_element (id_categorie_element)');
        $this->addSql('CREATE INDEX IDX_71BC9231C4ED1CA4 ON contient_element (id_genre_element)');
        $this->addSql('CREATE INDEX IDX_71BC9231883FA27F ON contient_element (id_nombre_element)');
        $this->addSql('CREATE TABLE element_biblio (id_element INT NOT NULL, id_biblio INT NOT NULL, reference_element VARCHAR(255) DEFAULT NULL, commentaire_fr VARCHAR(255) DEFAULT NULL, commentaire_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id_element, id_biblio))');
        $this->addSql('CREATE INDEX IDX_501BB2B19FDDF749 ON element_biblio (id_element)');
        $this->addSql('CREATE INDEX IDX_501BB2B1FF3B0EC8 ON element_biblio (id_biblio)');
        $this->addSql('CREATE TABLE genre_element (id INT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE traduction_element (id INT NOT NULL, id_element INT DEFAULT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7F7FF2499FDDF749 ON traduction_element (id_element)');
        $this->addSql('CREATE TABLE categorie_element (id INT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE element_categorie (id_categorie_element INT NOT NULL, id_element INT NOT NULL, PRIMARY KEY(id_categorie_element, id_element))');
        $this->addSql('CREATE INDEX IDX_E176F81FEFF4988C ON element_categorie (id_categorie_element)');
        $this->addSql('CREATE INDEX IDX_E176F81F9FDDF749 ON element_categorie (id_element)');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E3964A5A8EA FOREIGN KEY (id_nature_element) REFERENCES nature_element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39C68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE elements_theonymes ADD CONSTRAINT FK_A4D66CFA1BB9D5A2 FOREIGN KEY (id_parent) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE elements_theonymes ADD CONSTRAINT FK_A4D66CFA1280B94F FOREIGN KEY (id_enfant) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contient_element ADD CONSTRAINT FK_71BC92312A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contient_element ADD CONSTRAINT FK_71BC92319FDDF749 FOREIGN KEY (id_element) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contient_element ADD CONSTRAINT FK_71BC9231EFF4988C FOREIGN KEY (id_categorie_element) REFERENCES categorie_element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contient_element ADD CONSTRAINT FK_71BC9231C4ED1CA4 FOREIGN KEY (id_genre_element) REFERENCES genre_element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contient_element ADD CONSTRAINT FK_71BC9231883FA27F FOREIGN KEY (id_nombre_element) REFERENCES nombre_element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE element_biblio ADD CONSTRAINT FK_501BB2B19FDDF749 FOREIGN KEY (id_element) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE element_biblio ADD CONSTRAINT FK_501BB2B1FF3B0EC8 FOREIGN KEY (id_biblio) REFERENCES biblio (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE traduction_element ADD CONSTRAINT FK_7F7FF2499FDDF749 FOREIGN KEY (id_element) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE element_categorie ADD CONSTRAINT FK_E176F81FEFF4988C FOREIGN KEY (id_categorie_element) REFERENCES categorie_element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE element_categorie ADD CONSTRAINT FK_E176F81F9FDDF749 FOREIGN KEY (id_element) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE elements_theonymes DROP CONSTRAINT FK_A4D66CFA1BB9D5A2');
        $this->addSql('ALTER TABLE elements_theonymes DROP CONSTRAINT FK_A4D66CFA1280B94F');
        $this->addSql('ALTER TABLE contient_element DROP CONSTRAINT FK_71BC92319FDDF749');
        $this->addSql('ALTER TABLE element_biblio DROP CONSTRAINT FK_501BB2B19FDDF749');
        $this->addSql('ALTER TABLE traduction_element DROP CONSTRAINT FK_7F7FF2499FDDF749');
        $this->addSql('ALTER TABLE element_categorie DROP CONSTRAINT FK_E176F81F9FDDF749');
        $this->addSql('ALTER TABLE contient_element DROP CONSTRAINT FK_71BC9231883FA27F');
        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E3964A5A8EA');
        $this->addSql('ALTER TABLE contient_element DROP CONSTRAINT FK_71BC9231C4ED1CA4');
        $this->addSql('ALTER TABLE contient_element DROP CONSTRAINT FK_71BC9231EFF4988C');
        $this->addSql('ALTER TABLE element_categorie DROP CONSTRAINT FK_E176F81FEFF4988C');
        $this->addSql('DROP SEQUENCE element_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE nombre_element_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE nature_element_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE genre_element_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE traduction_element_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE categorie_element_id_seq CASCADE');
        $this->addSql('DROP TABLE element');
        $this->addSql('DROP TABLE elements_theonymes');
        $this->addSql('DROP TABLE nombre_element');
        $this->addSql('DROP TABLE nature_element');
        $this->addSql('DROP TABLE contient_element');
        $this->addSql('DROP TABLE element_biblio');
        $this->addSql('DROP TABLE genre_element');
        $this->addSql('DROP TABLE traduction_element');
        $this->addSql('DROP TABLE categorie_element');
        $this->addSql('DROP TABLE element_categorie');
    }
}
