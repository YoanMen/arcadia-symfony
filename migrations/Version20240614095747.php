<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614095747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE species ADD commun_name VARCHAR(80) NOT NULL, ADD genre VARCHAR(60) NOT NULL, ADD family VARCHAR(60) NOT NULL, ADD ordre VARCHAR(60) NOT NULL, DROP species');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE species ADD species VARCHAR(180) NOT NULL, DROP commun_name, DROP genre, DROP family, DROP ordre');
    }
}
