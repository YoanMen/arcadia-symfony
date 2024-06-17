<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617140510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_food CHANGE employee_id employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE habitat_comment CHANGE veterinary_id veterinary_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habitat_comment CHANGE veterinary_id veterinary_id INT NOT NULL');
        $this->addSql('ALTER TABLE animal_food CHANGE employee_id employee_id INT NOT NULL');
    }
}
