<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190308142424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE agent DROP CONSTRAINT fk_268b9c9d343700e0');
        $this->addSql('DROP INDEX uniq_268b9c9d343700e0');
        $this->addSql('ALTER TABLE agent RENAME COLUMN localisation_decouverte_id TO localisation_id');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DC68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_268B9C9DC68BE09C ON agent (localisation_id)');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT fk_326ec63f343700e0');
        $this->addSql('DROP INDEX uniq_326ec63f343700e0');
        $this->addSql('ALTER TABLE attestation ALTER id_source SET NOT NULL');
        $this->addSql('ALTER TABLE attestation RENAME COLUMN localisation_decouverte_id TO localisation_id');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT FK_326EC63FC68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_326EC63FC68BE09C ON attestation (localisation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE agent DROP CONSTRAINT FK_268B9C9DC68BE09C');
        $this->addSql('DROP INDEX UNIQ_268B9C9DC68BE09C');
        $this->addSql('ALTER TABLE agent RENAME COLUMN localisation_id TO localisation_decouverte_id');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT fk_268b9c9d343700e0 FOREIGN KEY (localisation_decouverte_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_268b9c9d343700e0 ON agent (localisation_decouverte_id)');
        $this->addSql('ALTER TABLE attestation DROP CONSTRAINT FK_326EC63FC68BE09C');
        $this->addSql('DROP INDEX UNIQ_326EC63FC68BE09C');
        $this->addSql('ALTER TABLE attestation ALTER id_source DROP NOT NULL');
        $this->addSql('ALTER TABLE attestation RENAME COLUMN localisation_id TO localisation_decouverte_id');
        $this->addSql('ALTER TABLE attestation ADD CONSTRAINT fk_326ec63f343700e0 FOREIGN KEY (localisation_decouverte_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_326ec63f343700e0 ON attestation (localisation_decouverte_id)');
    }
}
