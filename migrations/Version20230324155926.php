<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use DoctrineMigrations\Traits\ResetsViews;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324155926 extends AbstractMigration
{
    use ResetsViews;

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE traduction_attestation ALTER nom_fr TYPE TEXT');
        $this->addSql('ALTER TABLE traduction_attestation ALTER nom_en TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE traduction_attestation ALTER nom_fr TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE traduction_attestation ALTER nom_en TYPE VARCHAR(1000)');
    }
}
