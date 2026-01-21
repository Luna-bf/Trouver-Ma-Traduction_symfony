<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260121172956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile ADD profile_picture_name VARCHAR(500) NOT NULL, ADD thumbnail_name VARCHAR(500) NOT NULL, DROP profile_picture_url, DROP thumbnail_url, CHANGE phone_number phone VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile ADD profile_picture_url VARCHAR(500) NOT NULL, ADD thumbnail_url VARCHAR(500) NOT NULL, DROP profile_picture_name, DROP thumbnail_name, CHANGE phone phone_number VARCHAR(20) NOT NULL');
    }
}
