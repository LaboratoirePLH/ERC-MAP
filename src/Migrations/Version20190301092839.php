<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190301092839 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE projet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE projet (id SMALLINT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE projet_chercheur (id_projet SMALLINT NOT NULL, id_chercheur INT NOT NULL, PRIMARY KEY(id_projet, id_chercheur))');
        $this->addSql('CREATE INDEX IDX_BE2C7C8476222944 ON projet_chercheur (id_projet)');
        $this->addSql('CREATE INDEX IDX_BE2C7C841DE72777 ON projet_chercheur (id_chercheur)');
        $this->addSql('ALTER TABLE projet_chercheur ADD CONSTRAINT FK_BE2C7C8476222944 FOREIGN KEY (id_projet) REFERENCES projet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projet_chercheur ADD CONSTRAINT FK_BE2C7C841DE72777 FOREIGN KEY (id_chercheur) REFERENCES chercheur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source ADD id_projet SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F73C18272 FOREIGN KEY (id_projet) REFERENCES projet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5F8A7F73C18272 ON source (id_projet)');

        $this->addSql("INSERT INTO public.projet (id, nom_fr, nom_en) VALUES (1, 'Laboratoire PLH', 'PLH Laboratory');");
        $this->addSql("INSERT INTO public.projet_chercheur SELECT 1, chercheur.id FROM chercheur;");
        $this->addSql("UPDATE public.source SET id_projet = 1;");
        $this->addSql("ALTER TABLE public.source ALTER COLUMN id_projet SET NOT NULL;");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE projet_chercheur DROP CONSTRAINT FK_BE2C7C8476222944');
        $this->addSql('ALTER TABLE source DROP CONSTRAINT FK_5F8A7F73C18272');
        $this->addSql('DROP SEQUENCE projet_id_seq CASCADE');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE projet_chercheur');
        $this->addSql('DROP INDEX IDX_5F8A7F73C18272');
        $this->addSql('ALTER TABLE source DROP projet_id');
    }
}
