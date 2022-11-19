<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409104135 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparations DROP FOREIGN KEY FK_953FFFD3DA088960');
        $this->addSql('DROP INDEX IDX_953FFFD3DA088960 ON reparations');
        $this->addSql('ALTER TABLE reparations DROP link_user_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparations ADD link_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reparations ADD CONSTRAINT FK_953FFFD3DA088960 FOREIGN KEY (link_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_953FFFD3DA088960 ON reparations (link_user_id)');
    }
}
