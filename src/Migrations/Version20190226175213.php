<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190226175213 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE source_titre_cite');
        $this->addSql('ALTER TABLE source DROP citation');
        $this->addSql('ALTER TABLE datation DROP post_quem_citation');
        $this->addSql('ALTER TABLE datation DROP ante_quem_citation');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE source_titre_cite (id_source INT NOT NULL, id_titre INT NOT NULL, PRIMARY KEY(id_source, id_titre))');
        $this->addSql('CREATE INDEX idx_39d512b311f20684 ON source_titre_cite (id_titre)');
        $this->addSql('CREATE INDEX idx_39d512b379bdca9e ON source_titre_cite (id_source)');
        $this->addSql('ALTER TABLE source_titre_cite ADD CONSTRAINT fk_39d512b379bdca9e FOREIGN KEY (id_source) REFERENCES source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_titre_cite ADD CONSTRAINT fk_39d512b311f20684 FOREIGN KEY (id_titre) REFERENCES titre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE datation ADD post_quem_citation SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE datation ADD ante_quem_citation SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE source ADD citation BOOLEAN DEFAULT NULL');
    }
}
