<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191203193557 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tag_name VARCHAR(30) NOT NULL)');
        $this->addSql('CREATE TABLE article_tag (article_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(article_id, tag_id))');
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, title, contenu, created_at, updated_at, featured_image, slug, user_id_id FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, contenu CLOB NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, featured_image VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, user_id_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO article (id, title, contenu, created_at, updated_at, featured_image, slug, user_id_id) SELECT id, title, contenu, created_at, updated_at, featured_image, slug, user_id_id FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE article_tag');
        $this->addSql('ALTER TABLE article ADD COLUMN tag VARCHAR(30) NOT NULL COLLATE BINARY');
    }
}
