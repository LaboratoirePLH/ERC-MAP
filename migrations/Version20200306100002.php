<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once(__DIR__ . '/Traits/ResetsViews.php');


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use DoctrineMigrations\Traits\ResetsViews;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200306100002 extends AbstractMigration
{
    use ResetsViews;

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE traduction_attestation ALTER nom_fr TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE traduction_attestation ALTER nom_en TYPE VARCHAR(1000)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE traduction_attestation ALTER nom_fr TYPE VARCHAR(500)');
        $this->addSql('ALTER TABLE traduction_attestation ALTER nom_en TYPE VARCHAR(500)');
    }
}
