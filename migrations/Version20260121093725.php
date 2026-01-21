<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260121093725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, profile_picture_url VARCHAR(500) NOT NULL, thumbnail_url VARCHAR(500) NOT NULL, description VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(20) NOT NULL, account_id INT NOT NULL, UNIQUE INDEX UNIQ_8157AA0F9B6B5FBA (account_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE translation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_file_format_url VARCHAR(500) NOT NULL, file_viewer_url VARCHAR(255) NOT NULL, translation_type VARCHAR(255) NOT NULL, translation_style VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, profile_id INT NOT NULL, INDEX IDX_B469456FCCFA12B8 (profile_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0F9B6B5FBA');
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456FCCFA12B8');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE translation');
    }
}
