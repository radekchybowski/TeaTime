<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002142526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE teas_tags (tea_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_878103B66A399A0D (tea_id), INDEX IDX_878103B6BAD26311 (tag_id), PRIMARY KEY(tea_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE teas_tags ADD CONSTRAINT FK_878103B66A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teas_tags ADD CONSTRAINT FK_878103B6BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tea_tag DROP FOREIGN KEY FK_16869A376A399A0D');
        $this->addSql('ALTER TABLE tea_tag DROP FOREIGN KEY FK_16869A37BAD26311');
        $this->addSql('DROP TABLE tea_tag');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tea_tag (tea_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_16869A37BAD26311 (tag_id), INDEX IDX_16869A376A399A0D (tea_id), PRIMARY KEY(tea_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tea_tag ADD CONSTRAINT FK_16869A376A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tea_tag ADD CONSTRAINT FK_16869A37BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teas_tags DROP FOREIGN KEY FK_878103B66A399A0D');
        $this->addSql('ALTER TABLE teas_tags DROP FOREIGN KEY FK_878103B6BAD26311');
        $this->addSql('DROP TABLE teas_tags');
    }
}
