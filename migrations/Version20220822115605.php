<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220822115605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer ADD short_description LONGTEXT DEFAULT NULL, CHANGE delai_at delai_at DATETIME DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT NULL, CHANGE programme programme LONGTEXT DEFAULT NULL, CHANGE nombre_de_jour nombre_de_jour INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer_programme ADD offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer_programme ADD CONSTRAINT FK_9E19C43B53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('CREATE INDEX IDX_9E19C43B53C674EE ON offer_programme (offer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP short_description, CHANGE delai_at delai_at DATETIME NOT NULL, CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE programme programme LONGTEXT NOT NULL, CHANGE nombre_de_jour nombre_de_jour INT NOT NULL');
        $this->addSql('ALTER TABLE offer_programme DROP FOREIGN KEY FK_9E19C43B53C674EE');
        $this->addSql('DROP INDEX IDX_9E19C43B53C674EE ON offer_programme');
        $this->addSql('ALTER TABLE offer_programme DROP offer_id');
    }
}
