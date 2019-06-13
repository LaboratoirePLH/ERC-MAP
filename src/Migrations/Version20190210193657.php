<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190210193657 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE source ADD localisation_decouverte_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE source ADD localisation_origine_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F73343700E0 FOREIGN KEY (localisation_decouverte_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F73C3FF2F4A FOREIGN KEY (localisation_origine_id) REFERENCES localisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F8A7F73343700E0 ON source (localisation_decouverte_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F8A7F73C3FF2F4A ON source (localisation_origine_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE source DROP CONSTRAINT FK_5F8A7F73343700E0');
        $this->addSql('ALTER TABLE source DROP CONSTRAINT FK_5F8A7F73C3FF2F4A');
        $this->addSql('DROP INDEX UNIQ_5F8A7F73343700E0');
        $this->addSql('DROP INDEX UNIQ_5F8A7F73C3FF2F4A');
        $this->addSql('ALTER TABLE source DROP localisation_decouverte_id');
        $this->addSql('ALTER TABLE source DROP localisation_origine_id');
    }
}
