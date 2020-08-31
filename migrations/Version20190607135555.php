<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607135555 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE element_biblio DROP commentaire_fr');
        $this->addSql('ALTER TABLE element_biblio DROP commentaire_en');
        $this->addSql('ALTER TABLE source_biblio DROP commentaire_fr');
        $this->addSql('ALTER TABLE source_biblio DROP commentaire_en');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE source_biblio ADD commentaire_fr TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE source_biblio ADD commentaire_en TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE element_biblio ADD commentaire_fr TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE element_biblio ADD commentaire_en TEXT DEFAULT NULL');
    }
}
