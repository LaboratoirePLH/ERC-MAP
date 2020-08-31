<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190620143537 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE formule SET formule = replace(formule, '[', '{');");
        $this->addSql("UPDATE formule SET formule = replace(formule, ']', '}');");
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE formule SET formule = replace(formule, '{', '[');");
        $this->addSql("UPDATE formule SET formule = replace(formule, '}', ']');");

    }
}
