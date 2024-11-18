<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118093615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE is_deleted is_deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE slider ADD is_video TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE team ADD is_active TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE is_deleted is_deleted TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE slider DROP is_video');
        $this->addSql('ALTER TABLE team DROP is_active');
    }
}
