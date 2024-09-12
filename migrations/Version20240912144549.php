<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240912144549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A6A399A0D');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A6A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A6A399A0D');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A6A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
