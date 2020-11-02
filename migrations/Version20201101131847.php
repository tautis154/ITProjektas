<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201101131847 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, fk_specialist_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, identity_id VARCHAR(255) NOT NULL, INDEX IDX_81398E091F5A79D8 (fk_specialist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor_specialty (id INT AUTO_INCREMENT NOT NULL, fk_specialist_id INT NOT NULL, fk_specialty_id INT NOT NULL, INDEX IDX_2F74C7071F5A79D8 (fk_specialist_id), INDEX IDX_2F74C707BBE8310F (fk_specialty_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE office (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialist (id INT AUTO_INCREMENT NOT NULL, fk_office_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, how_many_appointed INT DEFAULT NULL, UNIQUE INDEX UNIQ_C2274AF4F85E0677 (username), INDEX IDX_C2274AF49EF915B4 (fk_office_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E091F5A79D8 FOREIGN KEY (fk_specialist_id) REFERENCES specialist (id)');
        $this->addSql('ALTER TABLE doctor_specialty ADD CONSTRAINT FK_2F74C7071F5A79D8 FOREIGN KEY (fk_specialist_id) REFERENCES specialist (id)');
        $this->addSql('ALTER TABLE doctor_specialty ADD CONSTRAINT FK_2F74C707BBE8310F FOREIGN KEY (fk_specialty_id) REFERENCES specialty (id)');
        $this->addSql('ALTER TABLE specialist ADD CONSTRAINT FK_C2274AF49EF915B4 FOREIGN KEY (fk_office_id) REFERENCES office (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialist DROP FOREIGN KEY FK_C2274AF49EF915B4');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E091F5A79D8');
        $this->addSql('ALTER TABLE doctor_specialty DROP FOREIGN KEY FK_2F74C7071F5A79D8');
        $this->addSql('ALTER TABLE doctor_specialty DROP FOREIGN KEY FK_2F74C707BBE8310F');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE doctor_specialty');
        $this->addSql('DROP TABLE office');
        $this->addSql('DROP TABLE specialist');
        $this->addSql('DROP TABLE specialty');
    }
}
