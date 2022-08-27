<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220812200540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE users (
            id SERIAL NOT NULL, 
            login VARCHAR(16) NOT NULL UNIQUE, 
            nickname VARCHAR(24) NOT NULL,
            email VARCHAR(128),
            password VARCHAR(128) DEFAULT NULL,
            access_level VARCHAR(20) DEFAULT 'user',
            created_at TIMESTAMP,
            updated_at TIMESTAMP,
            deleted_at TIMESTAMP,
            PRIMARY KEY(id)
            )"
        );
        $this->addSql('CREATE UNIQUE INDEX users_login_uindex ON users (login)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
