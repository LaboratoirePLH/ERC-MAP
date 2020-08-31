<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once(__DIR__ . '/Traits/ResetsViews.php');

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use DoctrineMigrations\Traits\ResetsViews;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200831154006 extends AbstractMigration
{
    use ResetsViews;

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE migration_versions');
        $this->addSql('ALTER TABLE element_biblio ALTER reference_element TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE source_biblio ALTER reference_source TYPE VARCHAR(1000)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE migration_versions (version VARCHAR(14) NOT NULL, executed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(version))');
        $this->addSql('COMMENT ON COLUMN migration_versions.executed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE element_biblio ALTER reference_element TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE source_biblio ALTER reference_source TYPE VARCHAR(255)');
    }
}
