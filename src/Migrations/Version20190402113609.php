<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402113609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bid DROP INDEX UNIQ_4AF2B3F316A2B381, ADD INDEX IDX_4AF2B3F316A2B381 (book_id)');
        $this->addSql('ALTER TABLE bid DROP INDEX UNIQ_4AF2B3F3A76ED395, ADD INDEX IDX_4AF2B3F3A76ED395 (user_id)');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3A76ED395');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3A76ED395 FOREIGN KEY (user_id) REFERENCES book (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bid DROP INDEX IDX_4AF2B3F3A76ED395, ADD UNIQUE INDEX UNIQ_4AF2B3F3A76ED395 (user_id)');
        $this->addSql('ALTER TABLE bid DROP INDEX IDX_4AF2B3F316A2B381, ADD UNIQUE INDEX UNIQ_4AF2B3F316A2B381 (book_id)');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3A76ED395');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
