<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607200341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE biblio ADD verrou_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biblio ADD CONSTRAINT FK_D90CBB251E25C1F6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D90CBB251E25C1F6 ON biblio (verrou_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE biblio DROP CONSTRAINT FK_D90CBB251E25C1F6');
        $this->addSql('DROP INDEX IDX_D90CBB251E25C1F6');
        $this->addSql('ALTER TABLE biblio DROP verrou_id');
    }
}
