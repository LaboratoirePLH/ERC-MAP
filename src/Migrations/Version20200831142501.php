<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200831142501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE element_biblio ALTER reference_element TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE source_biblio ALTER reference_source TYPE VARCHAR(1000)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE source_biblio ALTER reference_source TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE element_biblio ALTER reference_element TYPE VARCHAR(255)');
    }
}
