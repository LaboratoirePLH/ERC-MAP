<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190313112711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE element ADD user_creation_id INT NOT NULL');
        $this->addSql('ALTER TABLE element ADD user_edition_id INT NOT NULL');
        $this->addSql('ALTER TABLE element ADD date_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE element ADD date_modification TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE element ADD version INT NOT NULL');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E399DE46F0F FOREIGN KEY (user_creation_id) REFERENCES chercheur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39D34FDCB2 FOREIGN KEY (user_edition_id) REFERENCES chercheur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_41405E399DE46F0F ON element (user_creation_id)');
        $this->addSql('CREATE INDEX IDX_41405E39D34FDCB2 ON element (user_edition_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E399DE46F0F');
        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E39D34FDCB2');
        $this->addSql('DROP INDEX IDX_41405E399DE46F0F');
        $this->addSql('DROP INDEX IDX_41405E39D34FDCB2');
        $this->addSql('ALTER TABLE element DROP user_creation_id');
        $this->addSql('ALTER TABLE element DROP user_edition_id');
        $this->addSql('ALTER TABLE element DROP date_creation');
        $this->addSql('ALTER TABLE element DROP date_modification');
        $this->addSql('ALTER TABLE element DROP version');
    }
}
