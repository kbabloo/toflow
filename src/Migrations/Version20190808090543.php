<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190808090543 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post ADD repost_id INT DEFAULT NULL, CHANGE published published TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D76B28D70 FOREIGN KEY (repost_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D76B28D70 ON post (repost_id)');
        $this->addSql('ALTER TABLE user CHANGE created created TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D76B28D70');
        $this->addSql('DROP INDEX IDX_5A8A6C8D76B28D70 ON post');
        $this->addSql('ALTER TABLE post DROP repost_id, CHANGE published published DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
