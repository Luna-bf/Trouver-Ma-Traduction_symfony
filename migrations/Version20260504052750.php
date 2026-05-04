<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260504052750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translation ADD profile_id INT NOT NULL');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456FCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('CREATE INDEX IDX_B469456FCCFA12B8 ON translation (profile_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456FCCFA12B8');
        $this->addSql('DROP INDEX IDX_B469456FCCFA12B8 ON translation');
        $this->addSql('ALTER TABLE translation DROP profile_id');
    }
}
