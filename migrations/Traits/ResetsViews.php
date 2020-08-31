<?php

namespace DoctrineMigrations\Traits;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\Exception\MigrationException;

trait ResetsViews
{
    /**
     * @throws MigrationException|DBALException
     */
    public function preUp(Schema $schema): void
    {
        // Drop views
        $this->connection->exec(
            file_get_contents(
                realpath(implode(DIRECTORY_SEPARATOR, [
                    __DIR__,
                    '..',
                    'scripts',
                    'drop_views.sql'
                ]))
            )
        );
    }

    /**
     * @throws MigrationException|DBALException
     */
    public function preDown(Schema $schema): void
    {
        // Drop views
        $this->connection->exec(
            file_get_contents(
                realpath(implode(DIRECTORY_SEPARATOR, [
                    __DIR__,
                    '..',
                    'scripts',
                    'drop_views.sql'
                ]))
            )
        );
    }

    /**
     * @throws MigrationException|DBALException
     */
    public function postUp(Schema $schema): void
    {
        // Create views
        $this->connection->exec(
            file_get_contents(
                realpath(implode(DIRECTORY_SEPARATOR, [
                    __DIR__,
                    '..',
                    'scripts',
                    'create_views.sql'
                ]))
            )
        );
    }

    /**
     * @throws MigrationException|DBALException
     */
    public function postDown(Schema $schema): void
    {
        // Create views
        $this->connection->exec(
            file_get_contents(
                realpath(implode(DIRECTORY_SEPARATOR, [
                    __DIR__,
                    '..',
                    'scripts',
                    'create_views.sql'
                ]))
            )
        );
    }
}
