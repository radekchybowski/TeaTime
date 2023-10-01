<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231001190120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tealists_teas (tealist_id INT NOT NULL, tea_id INT NOT NULL, INDEX IDX_7D2FD02FB65FE0AF (tealist_id), INDEX IDX_7D2FD02F6A399A0D (tea_id), PRIMARY KEY(tealist_id, tea_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tealists_teas ADD CONSTRAINT FK_7D2FD02FB65FE0AF FOREIGN KEY (tealist_id) REFERENCES tealist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tealists_teas ADD CONSTRAINT FK_7D2FD02F6A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tealists_teas DROP FOREIGN KEY FK_7D2FD02FB65FE0AF');
        $this->addSql('ALTER TABLE tealists_teas DROP FOREIGN KEY FK_7D2FD02F6A399A0D');
        $this->addSql('DROP TABLE tealists_teas');
    }
}
