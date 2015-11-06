<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151107000849 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE advert (id INT UNSIGNED AUTO_INCREMENT NOT NULL, url VARCHAR(300) NOT NULL, model VARCHAR(100) NOT NULL, body VARCHAR(100) DEFAULT NULL, color VARCHAR(100) DEFAULT NULL, helm VARCHAR(100) DEFAULT NULL, city VARCHAR(100) NOT NULL, engine VARCHAR(100) DEFAULT NULL, power VARCHAR(100) DEFAULT NULL, transmission VARCHAR(100) DEFAULT NULL, gear VARCHAR(100) DEFAULT NULL, mileage VARCHAR(100) DEFAULT NULL, additional LONGTEXT DEFAULT NULL, price INT UNSIGNED NOT NULL, bulletin_id VARCHAR(300) DEFAULT NULL, bulletin_date DATETIME NOT NULL, create_at DATETIME NOT NULL, year INT UNSIGNED NOT NULL, new TINYINT(1) DEFAULT NULL, maker VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE advert');
    }
}
