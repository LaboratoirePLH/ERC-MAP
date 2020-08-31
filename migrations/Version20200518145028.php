<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200518145028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_268b9c9dc68be09c');
        $this->addSql('CREATE INDEX IDX_268B9C9DC68BE09C ON agent (localisation_id)');
        $this->addSql('DROP INDEX uniq_5f8a7f73343700e0');
        $this->addSql('DROP INDEX uniq_5f8a7f73c3ff2f4a');
        $this->addSql('CREATE INDEX IDX_5F8A7F73343700E0 ON source (localisation_decouverte_id)');
        $this->addSql('CREATE INDEX IDX_5F8A7F73C3FF2F4A ON source (localisation_origine_id)');
        $this->addSql('DROP INDEX uniq_326ec63fc68be09c');
        $this->addSql('CREATE INDEX IDX_326EC63FC68BE09C ON attestation (localisation_id)');
        $this->addSql('DROP INDEX uniq_41405e39c68be09c');
        $this->addSql('CREATE INDEX IDX_41405E39C68BE09C ON element (localisation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX IDX_41405E39C68BE09C');
        $this->addSql('CREATE UNIQUE INDEX uniq_41405e39c68be09c ON element (localisation_id)');
        $this->addSql('DROP INDEX IDX_326EC63FC68BE09C');
        $this->addSql('CREATE UNIQUE INDEX uniq_326ec63fc68be09c ON attestation (localisation_id)');
        $this->addSql('DROP INDEX IDX_268B9C9DC68BE09C');
        $this->addSql('CREATE UNIQUE INDEX uniq_268b9c9dc68be09c ON agent (localisation_id)');
        $this->addSql('DROP INDEX IDX_5F8A7F73343700E0');
        $this->addSql('DROP INDEX IDX_5F8A7F73C3FF2F4A');
        $this->addSql('CREATE UNIQUE INDEX uniq_5f8a7f73343700e0 ON source (localisation_decouverte_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_5f8a7f73c3ff2f4a ON source (localisation_origine_id)');
    }
}
