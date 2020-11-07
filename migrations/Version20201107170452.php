<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201107170452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT fk_326ec63f1e25c1f6');
        $this->addSql('DROP INDEX idx_326ec63f1e25c1f6');
        $this->addSql('ALTER TABLE attestation DROP verrou_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attestation ADD verrou_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT fk_326ec63f1e25c1f6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_326ec63f1e25c1f6 ON attestation (verrou_id)');
    }
}
