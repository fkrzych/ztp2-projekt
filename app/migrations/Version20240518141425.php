<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240518141425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contacts (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, name VARCHAR(45) NOT NULL, phone VARCHAR(20) NOT NULL, note LONGTEXT DEFAULT NULL, INDEX IDX_33401573F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contacts_tags (contact_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_6FDD317FE7A1254A (contact_id), INDEX IDX_6FDD317FBAD26311 (tag_id), PRIMARY KEY(contact_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_33401573F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE contacts_tags ADD CONSTRAINT FK_6FDD317FE7A1254A FOREIGN KEY (contact_id) REFERENCES contacts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contacts_tags ADD CONSTRAINT FK_6FDD317FBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contacts DROP FOREIGN KEY FK_33401573F675F31B');
        $this->addSql('ALTER TABLE contacts_tags DROP FOREIGN KEY FK_6FDD317FE7A1254A');
        $this->addSql('ALTER TABLE contacts_tags DROP FOREIGN KEY FK_6FDD317FBAD26311');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE contacts_tags');
    }
}
