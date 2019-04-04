<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404153745 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE theonymes_implicites (id_parent INT NOT NULL, id_enfant INT NOT NULL, PRIMARY KEY(id_parent, id_enfant))');
        $this->addSql('CREATE INDEX IDX_F6A451291BB9D5A2 ON theonymes_implicites (id_parent)');
        $this->addSql('CREATE INDEX IDX_F6A451291280B94F ON theonymes_implicites (id_enfant)');
        $this->addSql('CREATE TABLE theonymes_construits (id_parent INT NOT NULL, id_enfant INT NOT NULL, PRIMARY KEY(id_parent, id_enfant))');
        $this->addSql('CREATE INDEX IDX_88F7723B1BB9D5A2 ON theonymes_construits (id_parent)');
        $this->addSql('CREATE INDEX IDX_88F7723B1280B94F ON theonymes_construits (id_enfant)');
        $this->addSql('ALTER TABLE theonymes_implicites ADD CONSTRAINT FK_F6A451291BB9D5A2 FOREIGN KEY (id_parent) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE theonymes_implicites ADD CONSTRAINT FK_F6A451291280B94F FOREIGN KEY (id_enfant) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE theonymes_construits ADD CONSTRAINT FK_88F7723B1BB9D5A2 FOREIGN KEY (id_parent) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE theonymes_construits ADD CONSTRAINT FK_88F7723B1280B94F FOREIGN KEY (id_enfant) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE elements_theonymes');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE elements_theonymes (id_parent INT NOT NULL, id_enfant INT NOT NULL, PRIMARY KEY(id_parent, id_enfant))');
        $this->addSql('CREATE INDEX idx_a4d66cfa1280b94f ON elements_theonymes (id_enfant)');
        $this->addSql('CREATE INDEX idx_a4d66cfa1bb9d5a2 ON elements_theonymes (id_parent)');
        $this->addSql('ALTER TABLE elements_theonymes ADD CONSTRAINT fk_a4d66cfa1bb9d5a2 FOREIGN KEY (id_parent) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE elements_theonymes ADD CONSTRAINT fk_a4d66cfa1280b94f FOREIGN KEY (id_enfant) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE theonymes_implicites');
        $this->addSql('DROP TABLE theonymes_construits');
    }
}
