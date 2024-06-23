<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240518152945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contacts_tags DROP FOREIGN KEY FK_6FDD317FBAD26311');
        $this->addSql('ALTER TABLE contacts_tags DROP FOREIGN KEY FK_6FDD317FE7A1254A');
        $this->addSql('ALTER TABLE events_tags DROP FOREIGN KEY FK_3EC905C71F7E88B');
        $this->addSql('ALTER TABLE events_tags DROP FOREIGN KEY FK_3EC905CBAD26311');
        $this->addSql('DROP TABLE contacts_tags');
        $this->addSql('DROP TABLE events_tags');
        $this->addSql('DROP TABLE tags');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contacts_tags (contact_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_6FDD317FBAD26311 (tag_id), INDEX IDX_6FDD317FE7A1254A (contact_id), PRIMARY KEY(contact_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE events_tags (event_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_3EC905CBAD26311 (tag_id), INDEX IDX_3EC905C71F7E88B (event_id), PRIMARY KEY(event_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX uq_tags_title (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE contacts_tags ADD CONSTRAINT FK_6FDD317FBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contacts_tags ADD CONSTRAINT FK_6FDD317FE7A1254A FOREIGN KEY (contact_id) REFERENCES contacts (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_tags ADD CONSTRAINT FK_3EC905C71F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_tags ADD CONSTRAINT FK_3EC905CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
