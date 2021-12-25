<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211225030054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, lectures_id INT DEFAULT NULL, fees_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_169E6FB9291E007 (lectures_id), UNIQUE INDEX UNIQ_169E6FB99C6BD325 (fees_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fee (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, costfee DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lecture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parents (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phonenumber INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sclass (id INT AUTO_INCREMENT NOT NULL, students_id INT DEFAULT NULL, courses_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_171D304E1AD8D010 (students_id), INDEX IDX_171D304EF9295384 (courses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, parents_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_B723AF33B706B6D3 (parents_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9291E007 FOREIGN KEY (lectures_id) REFERENCES lecture (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB99C6BD325 FOREIGN KEY (fees_id) REFERENCES fee (id)');
        $this->addSql('ALTER TABLE sclass ADD CONSTRAINT FK_171D304E1AD8D010 FOREIGN KEY (students_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE sclass ADD CONSTRAINT FK_171D304EF9295384 FOREIGN KEY (courses_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33B706B6D3 FOREIGN KEY (parents_id) REFERENCES parents (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sclass DROP FOREIGN KEY FK_171D304EF9295384');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB99C6BD325');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9291E007');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33B706B6D3');
        $this->addSql('ALTER TABLE sclass DROP FOREIGN KEY FK_171D304E1AD8D010');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE fee');
        $this->addSql('DROP TABLE lecture');
        $this->addSql('DROP TABLE parents');
        $this->addSql('DROP TABLE sclass');
        $this->addSql('DROP TABLE student');
    }
}
