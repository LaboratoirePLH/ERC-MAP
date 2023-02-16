<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230153834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE requete_enregistree_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE requete_enregistree (id INT NOT NULL, query TEXT NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, nom_en VARCHAR(255) DEFAULT NULL, commentaire_fr TEXT DEFAULT NULL, commentaire_en TEXT DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE requete_enregistree_id_seq CASCADE');
        $this->addSql('DROP TABLE requete_enregistree');
    }
}
