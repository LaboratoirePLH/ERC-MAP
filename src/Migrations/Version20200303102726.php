<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303102726 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE recherche_enregistree_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE recherche_enregistree (id INT NOT NULL, user_creation_id INT NOT NULL, nom VARCHAR(255) NOT NULL, mode VARCHAR(10) NOT NULL, criteria TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3EABA7A69DE46F0F ON recherche_enregistree (user_creation_id)');
        $this->addSql('ALTER TABLE recherche_enregistree ADD CONSTRAINT FK_3EABA7A69DE46F0F FOREIGN KEY (user_creation_id) REFERENCES chercheur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE recherche_enregistree_id_seq CASCADE');
        $this->addSql('DROP TABLE recherche_enregistree');
    }
}
