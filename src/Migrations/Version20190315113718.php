<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190315113718 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        // Import data
        $this->addSql('INSERT INTO public.chercheur (id, prenom_nom, username, mail, password, date_ajout, role, preference_langue) VALUES (10, \'Ginevra Benedetti\', \'gbenedetti\', \'ginevrabenedetti2404@gmail.com\', \'$2y$13$NA276iZbDPI5TP/SJVNvmes7pw0L4g9ayOfl1sl9gPEAAsELwZNR.\', \'2019-03-15\', \'user\', \'fr\');');
        $this->addSql('INSERT INTO public.chercheur (id, prenom_nom, username, mail, password, date_ajout, role, preference_langue) VALUES (11, \'Gian Franco Chiai\', \'gchiai\', \'g_chiai@yahoo.it\', \'$2y$13$NA276iZbDPI5TP/SJVNvmes7pw0L4g9ayOfl1sl9gPEAAsELwZNR.\', \'2019-03-15\', \'user\', \'fr\');');
        $this->addSql('INSERT INTO public.chercheur (id, prenom_nom, username, mail, password, date_ajout, role, preference_langue) VALUES (12, \'Aleksandra Kubiak\', \'akubiak\', \'aleksandra.kubiak-schneider@univ-tlse2.fr\', \'$2y$13$NA276iZbDPI5TP/SJVNvmes7pw0L4g9ayOfl1sl9gPEAAsELwZNR.\', \'2019-03-15\', \'user\', \'fr\');');
        $this->addSql('INSERT INTO public.chercheur (id, prenom_nom, username, mail, password, date_ajout, role, preference_langue) VALUES (13, \'Adeline Grand-Clément\', \'agrandclement\', \'adeline.grand-clement@univ-tlse2.fr\', \'$2y$13$NA276iZbDPI5TP/SJVNvmes7pw0L4g9ayOfl1sl9gPEAAsELwZNR.\', \'2019-03-15\', \'user\', \'fr\');');
        $this->addSql('INSERT INTO public.chercheur (id, prenom_nom, username, mail, password, date_ajout, role, preference_langue) VALUES (14, \'Laurent Bricault\', \'lbricault\', \'laurent.bricault@univ-tlse2.fr\', \'$2y$13$NA276iZbDPI5TP/SJVNvmes7pw0L4g9ayOfl1sl9gPEAAsELwZNR.\', \'2019-03-15\', \'user\', \'fr\');');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM public.chercheur WHERE username = \'gbenedetti\';');
        $this->addSql('DELETE FROM public.chercheur WHERE username = \'gchiai\';');
        $this->addSql('DELETE FROM public.chercheur WHERE username = \'akubiak\';');
        $this->addSql('DELETE FROM public.chercheur WHERE username = \'agrandclement\';');
        $this->addSql('DELETE FROM public.chercheur WHERE username = \'lbricault\';');
    }
}
