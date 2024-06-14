<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614124113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_information DROP INDEX IDX_8EC3CB3AB2A1D860, ADD UNIQUE INDEX UNIQ_8EC3CB3AB2A1D860 (species_id)');
        $this->addSql('ALTER TABLE animal_information CHANGE size_and_height size_and_height VARCHAR(255) NOT NULL, CHANGE lifespan lifespan VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_information DROP INDEX UNIQ_8EC3CB3AB2A1D860, ADD INDEX IDX_8EC3CB3AB2A1D860 (species_id)');
        $this->addSql('ALTER TABLE animal_information CHANGE size_and_height size_and_height VARCHAR(180) NOT NULL, CHANGE lifespan lifespan VARCHAR(180) NOT NULL');
    }
}
