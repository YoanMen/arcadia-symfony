<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240712152314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_image CHANGE image_name image_name VARCHAR(160) DEFAULT NULL, CHANGE alt alt VARCHAR(120) DEFAULT NULL');
        $this->addSql('ALTER TABLE habitat_image CHANGE image_name image_name VARCHAR(160) DEFAULT NULL, CHANGE alt alt VARCHAR(120) DEFAULT NULL');
        $this->addSql('ALTER TABLE service_image CHANGE image_name image_name VARCHAR(160) DEFAULT NULL, CHANGE alt alt VARCHAR(120) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_image CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE alt alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE habitat_image CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE alt alt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE service_image CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE alt alt VARCHAR(255) DEFAULT NULL');
    }
}
