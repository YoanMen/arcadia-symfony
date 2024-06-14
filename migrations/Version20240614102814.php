<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614102814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal ADD information_id INT NOT NULL');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F2EF03101 FOREIGN KEY (information_id) REFERENCES animal_information (id)');
        $this->addSql('CREATE INDEX IDX_6AAB231F2EF03101 ON animal (information_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231F2EF03101');
        $this->addSql('DROP INDEX IDX_6AAB231F2EF03101 ON animal');
        $this->addSql('ALTER TABLE animal DROP information_id');
    }
}
