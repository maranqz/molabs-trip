<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210221194037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7D3656A4E7927C74 (email), UNIQUE INDEX UNIQ_7D3656A4F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (code VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, region VARCHAR(10) NOT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, country_code VARCHAR(3) NOT NULL, started_at DATE NOT NULL, finished_at DATE NOT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_7656F53BB03A8386 (created_by_id), INDEX IDX_7656F53BF026BB7C (country_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BB03A8386 FOREIGN KEY (created_by_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BF026BB7C FOREIGN KEY (country_code) REFERENCES country (code)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BB03A8386');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BF026BB7C');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE trip');
    }
}
