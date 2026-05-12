<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512085134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY `FK_B469456FA76ED395`');
        $this->addSql('DROP INDEX IDX_B469456FA76ED395 ON translation');
        $this->addSql('ALTER TABLE translation ADD profile_id INT NOT NULL, DROP user_id');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('CREATE INDEX IDX_B469456FCCFA12B8 ON translation (profile_id)');
        $this->addSql('ALTER TABLE user DROP username, DROP profile_picture_name, DROP thumbnail_name, DROP description, DROP phone');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456FCCFA12B8');
        $this->addSql('DROP INDEX IDX_B469456FCCFA12B8 ON translation');
        $this->addSql('ALTER TABLE translation ADD user_id INT DEFAULT NULL, DROP profile_id');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT `FK_B469456FA76ED395` FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B469456FA76ED395 ON translation (user_id)');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) NOT NULL, ADD profile_picture_name VARCHAR(255) NOT NULL, ADD thumbnail_name VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD phone VARCHAR(20) NOT NULL');
    }
}
