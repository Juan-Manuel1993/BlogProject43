<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191204101724 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE fos_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:array)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479C05FB297 ON fos_user (confirmation_token)');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tag_name VARCHAR(30) NOT NULL)');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, contenu CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, featured_image VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, user_id_id INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE article_tag (article_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(article_id, tag_id))');
        $this->addSql('CREATE TABLE commentaires (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL, contenu VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, actif BOOLEAN NOT NULL, article_id_id INTEGER NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_tag');
        $this->addSql('DROP TABLE commentaires');
    }
}
