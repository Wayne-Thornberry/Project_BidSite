<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328162614 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book ADD submitter_name VARCHAR(255) NOT NULL, ADD submitter_id INT NOT NULL');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY bid_book_id_fk');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY bid_user_id_fk');
        $this->addSql('DROP INDEX bid_book_id_fk ON bid');
        $this->addSql('DROP INDEX bid_user_id_fk ON bid');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bid ADD CONSTRAINT bid_book_id_fk FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT bid_user_id_fk FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX bid_book_id_fk ON bid (book_id)');
        $this->addSql('CREATE INDEX bid_user_id_fk ON bid (user_id)');
        $this->addSql('ALTER TABLE book DROP submitter_name, DROP submitter_id');
    }
}
