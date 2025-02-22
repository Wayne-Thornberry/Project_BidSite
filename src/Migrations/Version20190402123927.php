<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402123927 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment ADD date_posted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE bid ADD date_posted DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD date_uploaded DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD date_created DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bid DROP date_posted');
        $this->addSql('ALTER TABLE book DROP date_uploaded');
        $this->addSql('ALTER TABLE comment DROP date_posted');
        $this->addSql('ALTER TABLE user DROP date_created');
    }
}
