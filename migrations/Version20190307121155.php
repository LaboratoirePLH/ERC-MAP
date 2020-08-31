<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190307121155 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO public.etat_fiche (id, nom_fr, nom_en) VALUES (1, 'En cours', 'In progress')");
        $this->addSql("INSERT INTO public.etat_fiche (id, nom_fr, nom_en) VALUES (2, 'Achevé', 'Completed')");
        $this->addSql("INSERT INTO public.etat_fiche (id, nom_fr, nom_en) VALUES (3, 'Relu', 'Revised')");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        // Remove data and reset sequences
        $this->addSql("TRUNCATE etat_fiche RESTART IDENTITY;");
    }
}
