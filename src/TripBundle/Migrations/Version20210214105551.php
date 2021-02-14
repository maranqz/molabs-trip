<?php

declare(strict_types=1);

namespace TripBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210214105551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (code VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, region VARCHAR(10) NOT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, country_code VARCHAR(3) DEFAULT NULL, created_at DATE NOT NULL, finished_at DATE NOT NULL, notes TEXT DEFAULT NULL, INDEX country_code (country_code), INDEX created_by (created_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BDE12AB56 FOREIGN KEY (created_by) REFERENCES account (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BF026BB7C FOREIGN KEY (country_code) REFERENCES country (code)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BDE12AB56');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BF026BB7C');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE trip');
    }
}
