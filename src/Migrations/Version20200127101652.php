<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200127101652 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE element ALTER etat_absolu TYPE TEXT');
        $this->addSql('ALTER TABLE element ALTER etat_absolu DROP DEFAULT');
        $this->addSql('ALTER TABLE biblio ALTER titre_abrege TYPE TEXT');
        $this->addSql('ALTER TABLE biblio ALTER titre_abrege DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE biblio ALTER titre_abrege TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE biblio ALTER titre_abrege DROP DEFAULT');
        $this->addSql('ALTER TABLE element ALTER etat_absolu TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE element ALTER etat_absolu DROP DEFAULT');
    }
}
