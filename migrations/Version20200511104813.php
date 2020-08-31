<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200511104813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE chercheur ADD actif BOOLEAN');
        $this->addSql("UPDATE chercheur SET actif = 't'");
        $this->addSql('ALTER TABLE chercheur ALTER COLUMN actif SET NOT NULL');
        $this->addSql('ALTER TABLE chercheur ALTER COLUMN actif SET DEFAULT FALSE');

        $this->addSql('ALTER TABLE chercheur ADD gestionnaire_comptes BOOLEAN');
        $this->addSql("UPDATE chercheur SET gestionnaire_comptes = 'f'");
        $this->addSql('ALTER TABLE chercheur ALTER COLUMN gestionnaire_comptes SET NOT NULL');
        $this->addSql('ALTER TABLE chercheur ALTER COLUMN gestionnaire_comptes SET DEFAULT FALSE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE chercheur DROP actif');
        $this->addSql('ALTER TABLE chercheur DROP gestionnaire_comptes');
    }
}
