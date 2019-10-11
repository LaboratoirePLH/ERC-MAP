<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191011152258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE contient_element DROP CONSTRAINT FK_71BC92319FDDF749');
        $this->addSql('ALTER TABLE contient_element DROP CONSTRAINT FK_71BC92312A7C0BC8');
        $this->addSql('ALTER TABLE contient_element ADD CONSTRAINT FK_71BC92319FDDF749 FOREIGN KEY (id_element) REFERENCES element (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contient_element ADD CONSTRAINT FK_71BC92312A7C0BC8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE contient_element DROP CONSTRAINT fk_71bc92312a7c0bc8');
        $this->addSql('ALTER TABLE contient_element DROP CONSTRAINT fk_71bc92319fddf749');
        $this->addSql('ALTER TABLE contient_element ADD CONSTRAINT fk_71bc92312a7c0bc8 FOREIGN KEY (id_attestation) REFERENCES attestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contient_element ADD CONSTRAINT fk_71bc92319fddf749 FOREIGN KEY (id_element) REFERENCES element (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
