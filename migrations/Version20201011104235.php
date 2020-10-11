<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201011104235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_fiche ADD open_access BOOLEAN');
        $this->addSql('UPDATE etat_fiche SET open_access = \'f\'');
        $this->addSql('ALTER TABLE etat_fiche ALTER COLUMN open_access SET NOT NULL');
        $this->addSql('ALTER TABLE etat_fiche ALTER COLUMN open_access SET DEFAULT FALSE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etat_fiche DROP open_access');
    }
}
