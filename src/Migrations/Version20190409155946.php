<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190409155946 extends AbstractMigration
{

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $tables = [
            "activite_agent",
            "agentivite",
            "auteur",
            "categorie_element",
            "categorie_materiau",
            "categorie_materiel",
            "categorie_occasion",
            "categorie_source",
            "categorie_support",
            "chercheur",
            "entite_politique",
            "etat_fiche",
            "genre",
            "genre_element",
            "grande_region",
            "langue",
            "materiau",
            "materiel",
            "nature",
            "nature_element",
            "nombre_element",
            "occasion",
            "pratique",
            "projet",
            "q_fonction",
            "q_topographie",
            "sous_region",
            "statut_affiche",
            "type_source",
            "type_support",
        ];
        foreach($tables as $table){
            $this->addSql("SELECT setval('{$table}_id_seq', COALESCE((SELECT MAX(id)+1 FROM {$table}), 1), false);");
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
