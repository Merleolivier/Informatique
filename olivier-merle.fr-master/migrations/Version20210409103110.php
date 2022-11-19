<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409103110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparations DROP FOREIGN KEY FK_953FFFD34363B11B');
        $this->addSql('DROP INDEX IDX_953FFFD34363B11B ON reparations');
        $this->addSql('ALTER TABLE reparations DROP user_repair_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparations ADD user_repair_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reparations ADD CONSTRAINT FK_953FFFD34363B11B FOREIGN KEY (user_repair_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_953FFFD34363B11B ON reparations (user_repair_id)');
    }
}
