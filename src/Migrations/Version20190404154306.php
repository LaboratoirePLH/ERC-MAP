<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404154306 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE element ALTER commentaire_fr TYPE TEXT');
        $this->addSql('ALTER TABLE element ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE element ALTER commentaire_en TYPE TEXT');
        $this->addSql('ALTER TABLE element ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation ALTER commentaire_fr TYPE TEXT');
        $this->addSql('ALTER TABLE localisation ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation ALTER commentaire_en TYPE TEXT');
        $this->addSql('ALTER TABLE localisation ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE agent ALTER commentaire_fr TYPE TEXT');
        $this->addSql('ALTER TABLE agent ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE agent ALTER commentaire_en TYPE TEXT');
        $this->addSql('ALTER TABLE agent ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE element_biblio ALTER commentaire_fr TYPE TEXT');
        $this->addSql('ALTER TABLE element_biblio ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE element_biblio ALTER commentaire_en TYPE TEXT');
        $this->addSql('ALTER TABLE element_biblio ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER commentaire_fr TYPE TEXT');
        $this->addSql('ALTER TABLE attestation ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER commentaire_en TYPE TEXT');
        $this->addSql('ALTER TABLE attestation ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE source_biblio ALTER commentaire_fr TYPE TEXT');
        $this->addSql('ALTER TABLE source_biblio ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE source_biblio ALTER commentaire_en TYPE TEXT');
        $this->addSql('ALTER TABLE source_biblio ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER commentaire_fr TYPE TEXT');
        $this->addSql('ALTER TABLE source ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER commentaire_en TYPE TEXT');
        $this->addSql('ALTER TABLE source ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE datation ALTER commentaire_fr TYPE TEXT');
        $this->addSql('ALTER TABLE datation ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE datation ALTER commentaire_en TYPE TEXT');
        $this->addSql('ALTER TABLE datation ALTER commentaire_en DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE element_biblio ALTER commentaire_fr TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE element_biblio ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE element_biblio ALTER commentaire_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE element_biblio ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE element ALTER commentaire_fr TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE element ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE element ALTER commentaire_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE element ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE source_biblio ALTER commentaire_fr TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE source_biblio ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE source_biblio ALTER commentaire_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE source_biblio ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE agent ALTER commentaire_fr TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE agent ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE agent ALTER commentaire_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE agent ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE datation ALTER commentaire_fr TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE datation ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE datation ALTER commentaire_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE datation ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation ALTER commentaire_fr TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE localisation ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE localisation ALTER commentaire_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE localisation ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER commentaire_fr TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE attestation ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE attestation ALTER commentaire_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE attestation ALTER commentaire_en DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER commentaire_fr TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE source ALTER commentaire_fr DROP DEFAULT');
        $this->addSql('ALTER TABLE source ALTER commentaire_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE source ALTER commentaire_en DROP DEFAULT');
    }
}
