<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151108000924 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE advert ADD hash VARCHAR(20) NOT NULL');
        $this->addSql('CREATE INDEX IDX_54F1F40BD1B862B8 ON advert (hash)');
        $this->addSql('CREATE INDEX IDX_SEARCH_HASH ON advert (search_id, hash)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_54F1F40BD1B862B8 ON advert');
        $this->addSql('DROP INDEX IDX_SEARCH_HASH ON advert');
        $this->addSql('ALTER TABLE advert DROP hash');
    }
}
