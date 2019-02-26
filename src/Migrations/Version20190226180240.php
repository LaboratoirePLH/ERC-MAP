<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190226180240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE biblio DROP CONSTRAINT fk_d90cbb252b41abf4');
        $this->addSql('DROP SEQUENCE corpus_id_seq CASCADE');
        $this->addSql('DROP TABLE corpus');
        $this->addSql('DROP INDEX idx_d90cbb252b41abf4');
        $this->addSql('ALTER TABLE biblio ADD est_corpus BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE biblio DROP corpus_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE corpus_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE corpus (id INT NOT NULL, nom VARCHAR(255) DEFAULT NULL, nom_complet TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE biblio ADD corpus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biblio DROP est_corpus');
        $this->addSql('ALTER TABLE biblio ADD CONSTRAINT fk_d90cbb252b41abf4 FOREIGN KEY (corpus_id) REFERENCES corpus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d90cbb252b41abf4 ON biblio (corpus_id)');
    }
}
