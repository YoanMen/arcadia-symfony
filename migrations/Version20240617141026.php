<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617141026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_food DROP FOREIGN KEY FK_931568C38E962C16');
        $this->addSql('ALTER TABLE animal_food CHANGE animal_id animal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT FK_931568C38E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE animal_report DROP FOREIGN KEY FK_7EDEB258D954EB99');
        $this->addSql('ALTER TABLE animal_report ADD CONSTRAINT FK_7EDEB258D954EB99 FOREIGN KEY (veterinary_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE habitat_comment DROP FOREIGN KEY FK_C86D6DCED954EB99');
        $this->addSql('ALTER TABLE habitat_comment ADD CONSTRAINT FK_C86D6DCED954EB99 FOREIGN KEY (veterinary_id) REFERENCES `user` (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habitat_comment DROP FOREIGN KEY FK_C86D6DCED954EB99');
        $this->addSql('ALTER TABLE habitat_comment ADD CONSTRAINT FK_C86D6DCED954EB99 FOREIGN KEY (veterinary_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE animal_report DROP FOREIGN KEY FK_7EDEB258D954EB99');
        $this->addSql('ALTER TABLE animal_report ADD CONSTRAINT FK_7EDEB258D954EB99 FOREIGN KEY (veterinary_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE animal_food DROP FOREIGN KEY FK_931568C38E962C16');
        $this->addSql('ALTER TABLE animal_food CHANGE animal_id animal_id INT NOT NULL');
        $this->addSql('ALTER TABLE animal_food ADD CONSTRAINT FK_931568C38E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
    }
}
