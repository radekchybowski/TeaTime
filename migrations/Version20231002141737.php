<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002141737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tealists (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, INDEX IDX_991A9706F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tealist_tea (tealist_id INT NOT NULL, tea_id INT NOT NULL, INDEX IDX_A38D5EB8B65FE0AF (tealist_id), INDEX IDX_A38D5EB86A399A0D (tea_id), PRIMARY KEY(tealist_id, tea_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tealists ADD CONSTRAINT FK_991A9706F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tealist_tea ADD CONSTRAINT FK_A38D5EB8B65FE0AF FOREIGN KEY (tealist_id) REFERENCES tealists (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tealist_tea ADD CONSTRAINT FK_A38D5EB86A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tealist DROP FOREIGN KEY FK_1CACCC56F675F31B');
        $this->addSql('ALTER TABLE tealists_teas DROP FOREIGN KEY FK_7D2FD02F6A399A0D');
        $this->addSql('DROP TABLE tealist');
        $this->addSql('DROP TABLE tealists_teas');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tealist (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1CACCC56F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tealists_teas (tealist_id INT NOT NULL, tea_id INT NOT NULL, INDEX IDX_7D2FD02F6A399A0D (tea_id), INDEX IDX_7D2FD02FB65FE0AF (tealist_id), PRIMARY KEY(tealist_id, tea_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tealist ADD CONSTRAINT FK_1CACCC56F675F31B FOREIGN KEY (author_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tealists_teas ADD CONSTRAINT FK_7D2FD02F6A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tealists DROP FOREIGN KEY FK_991A9706F675F31B');
        $this->addSql('ALTER TABLE tealist_tea DROP FOREIGN KEY FK_A38D5EB8B65FE0AF');
        $this->addSql('ALTER TABLE tealist_tea DROP FOREIGN KEY FK_A38D5EB86A399A0D');
        $this->addSql('DROP TABLE tealists');
        $this->addSql('DROP TABLE tealist_tea');
    }
}
