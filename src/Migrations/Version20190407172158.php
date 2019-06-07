<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190407172158 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E391E25C1F6');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E391E25C1F6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT FK_326EC63F1E25C1F6');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63F1E25C1F6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source DROP CONSTRAINT FK_5F8A7F731E25C1F6');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F731E25C1F6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE element DROP CONSTRAINT fk_41405e391e25c1f6');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT fk_41405e391e25c1f6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source DROP CONSTRAINT fk_5f8a7f731e25c1f6');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT fk_5f8a7f731e25c1f6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT fk_326ec63f1e25c1f6');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT fk_326ec63f1e25c1f6 FOREIGN KEY (verrou_id) REFERENCES verrou_entite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
