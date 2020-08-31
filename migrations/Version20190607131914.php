<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607131914 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE source_type_source (id_source INT NOT NULL, id_type_source INT NOT NULL, PRIMARY KEY(id_source, id_type_source))');
        $this->addSql('CREATE INDEX IDX_402244EB79BDCA9E ON source_type_source (id_source)');
        $this->addSql('CREATE INDEX IDX_402244EB644FD9BC ON source_type_source (id_type_source)');
        $this->addSql('ALTER TABLE source_type_source ADD CONSTRAINT FK_402244EB79BDCA9E FOREIGN KEY (id_source) REFERENCES source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_type_source ADD CONSTRAINT FK_402244EB644FD9BC FOREIGN KEY (id_type_source) REFERENCES type_source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('INSERT INTO source_type_source (id_source, id_type_source) SELECT id, type_source_id FROM source WHERE type_source_id IS NOT NULL');
        $this->addSql('ALTER TABLE source DROP CONSTRAINT fk_5f8a7f7321fa5d71');
        $this->addSql('DROP INDEX idx_5f8a7f7321fa5d71');
        $this->addSql('ALTER TABLE source DROP type_source_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE source ADD type_source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT fk_5f8a7f7321fa5d71 FOREIGN KEY (type_source_id) REFERENCES type_source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5f8a7f7321fa5d71 ON source (type_source_id)');
        $this->addSql('UPDATE source s SET type_source_id = (SELECT sts.id_type_source FROM source_type_source sts WHERE sts.id_source = s.id LIMIT 1) WHERE s.id IN (SELECT DISTINCT sts2.id_source FROM source_type_source sts2)');
        $this->addSql('DROP TABLE source_type_source');
    }
}
