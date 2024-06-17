<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617141136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_food DROP FOREIGN KEY FK_931568C38C03F15C');
        $this->addSql('ALTER TABLE animal_food DROP FOREIGN KEY FK_931568C38E962C16');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT FK_931568C38C03F15C FOREIGN KEY (employee_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT FK_931568C38E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_food DROP FOREIGN KEY FK_931568C38C03F15C');
        $this->addSql('ALTER TABLE animal_food DROP FOREIGN KEY FK_931568C38E962C16');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT FK_931568C38C03F15C FOREIGN KEY (employee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT FK_931568C38E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE SET NULL');
    }
}
