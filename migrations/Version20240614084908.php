<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614084908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advice (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(160) NOT NULL, advice LONGTEXT NOT NULL, approved TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, habitat_id INT NOT NULL, name VARCHAR(80) NOT NULL, slug VARCHAR(100) NOT NULL, INDEX IDX_6AAB231FAFFE2D26 (habitat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal_food (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', food VARCHAR(60) NOT NULL, quantity NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal_food_user (animal_food_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C01053F73551E8FE (animal_food_id), INDEX IDX_C01053F7A76ED395 (user_id), PRIMARY KEY(animal_food_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal_image (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, image_size INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E4CEDDAB8E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal_information (id INT AUTO_INCREMENT NOT NULL, species_id INT NOT NULL, uicn_id INT NOT NULL, region_id INT NOT NULL, description LONGTEXT NOT NULL, size_and_height VARCHAR(180) NOT NULL, lifespan VARCHAR(100) NOT NULL, INDEX IDX_8EC3CB3AB2A1D860 (species_id), INDEX IDX_8EC3CB3A90ACEFEF (uicn_id), INDEX IDX_8EC3CB3A98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal_report (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', statut VARCHAR(60) NOT NULL, food VARCHAR(60) NOT NULL, quantity NUMERIC(5, 2) NOT NULL, detail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal_report_user (animal_report_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5905E80BB37689AF (animal_report_id), INDEX IDX_5905E80BA76ED395 (user_id), PRIMARY KEY(animal_report_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auth_attempt (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, attempt INT NOT NULL, UNIQUE INDEX UNIQ_56C822959B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitat_comment (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', detail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitat_comment_user (habitat_comment_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_CF3DA031E2076BD (habitat_comment_id), INDEX IDX_CF3DA03A76ED395 (user_id), PRIMARY KEY(habitat_comment_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitat_image (id INT AUTO_INCREMENT NOT NULL, habitat_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, image_size INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9AD7E031AFFE2D26 (habitat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, region VARCHAR(120) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedules (id INT AUTO_INCREMENT NOT NULL, schedules LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, slug VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, information LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_image (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, image_size INT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6C4FE9B8ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE species (id INT AUTO_INCREMENT NOT NULL, species VARCHAR(180) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uicn (id INT AUTO_INCREMENT NOT NULL, uicn VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FAFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id)');
        $this->addSql('ALTER TABLE animal_food_user ADD CONSTRAINT FK_C01053F73551E8FE FOREIGN KEY (animal_food_id) REFERENCES animal_food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animal_food_user ADD CONSTRAINT FK_C01053F7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animal_image ADD CONSTRAINT FK_E4CEDDAB8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE animal_information ADD CONSTRAINT FK_8EC3CB3AB2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE animal_information ADD CONSTRAINT FK_8EC3CB3A90ACEFEF FOREIGN KEY (uicn_id) REFERENCES uicn (id)');
        $this->addSql('ALTER TABLE animal_information ADD CONSTRAINT FK_8EC3CB3A98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE animal_report_user ADD CONSTRAINT FK_5905E80BB37689AF FOREIGN KEY (animal_report_id) REFERENCES animal_report (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animal_report_user ADD CONSTRAINT FK_5905E80BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auth_attempt ADD CONSTRAINT FK_56C822959B6B5FBA FOREIGN KEY (account_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE habitat_comment_user ADD CONSTRAINT FK_CF3DA031E2076BD FOREIGN KEY (habitat_comment_id) REFERENCES habitat_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE habitat_comment_user ADD CONSTRAINT FK_CF3DA03A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE habitat_image ADD CONSTRAINT FK_9AD7E031AFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id)');
        $this->addSql('ALTER TABLE service_image ADD CONSTRAINT FK_6C4FE9B8ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FAFFE2D26');
        $this->addSql('ALTER TABLE animal_food_user DROP FOREIGN KEY FK_C01053F73551E8FE');
        $this->addSql('ALTER TABLE animal_food_user DROP FOREIGN KEY FK_C01053F7A76ED395');
        $this->addSql('ALTER TABLE animal_image DROP FOREIGN KEY FK_E4CEDDAB8E962C16');
        $this->addSql('ALTER TABLE animal_information DROP FOREIGN KEY FK_8EC3CB3AB2A1D860');
        $this->addSql('ALTER TABLE animal_information DROP FOREIGN KEY FK_8EC3CB3A90ACEFEF');
        $this->addSql('ALTER TABLE animal_information DROP FOREIGN KEY FK_8EC3CB3A98260155');
        $this->addSql('ALTER TABLE animal_report_user DROP FOREIGN KEY FK_5905E80BB37689AF');
        $this->addSql('ALTER TABLE animal_report_user DROP FOREIGN KEY FK_5905E80BA76ED395');
        $this->addSql('ALTER TABLE auth_attempt DROP FOREIGN KEY FK_56C822959B6B5FBA');
        $this->addSql('ALTER TABLE habitat_comment_user DROP FOREIGN KEY FK_CF3DA031E2076BD');
        $this->addSql('ALTER TABLE habitat_comment_user DROP FOREIGN KEY FK_CF3DA03A76ED395');
        $this->addSql('ALTER TABLE habitat_image DROP FOREIGN KEY FK_9AD7E031AFFE2D26');
        $this->addSql('ALTER TABLE service_image DROP FOREIGN KEY FK_6C4FE9B8ED5CA9E6');
        $this->addSql('DROP TABLE advice');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE animal_food');
        $this->addSql('DROP TABLE animal_food_user');
        $this->addSql('DROP TABLE animal_image');
        $this->addSql('DROP TABLE animal_information');
        $this->addSql('DROP TABLE animal_report');
        $this->addSql('DROP TABLE animal_report_user');
        $this->addSql('DROP TABLE auth_attempt');
        $this->addSql('DROP TABLE habitat');
        $this->addSql('DROP TABLE habitat_comment');
        $this->addSql('DROP TABLE habitat_comment_user');
        $this->addSql('DROP TABLE habitat_image');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE schedules');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE service_image');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE uicn');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
