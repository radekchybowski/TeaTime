<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231205233713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatars (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, filename VARCHAR(191) NOT NULL, UNIQUE INDEX UNIQ_B0C98520A76ED395 (user_id), UNIQUE INDEX uq_avatars_filename (filename), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, slug VARCHAR(64) NOT NULL, UNIQUE INDEX uq_categories_title (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, tea_id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5F9E962A6A399A0D (tea_id), INDEX IDX_5F9E962AF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ratings (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, tea_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', rating INT NOT NULL, INDEX IDX_CEB607C9F675F31B (author_id), INDEX IDX_CEB607C96A399A0D (tea_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, slug VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tealists (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, INDEX IDX_991A9706F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tealists_teas (tealist_id INT NOT NULL, tea_id INT NOT NULL, INDEX IDX_7D2FD02FB65FE0AF (tealist_id), INDEX IDX_7D2FD02F6A399A0D (tea_id), PRIMARY KEY(tealist_id, tea_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teas (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, ingredients VARCHAR(255) DEFAULT NULL, steep_time INT DEFAULT NULL, steep_temp INT DEFAULT NULL, region VARCHAR(64) DEFAULT NULL, vendor VARCHAR(64) DEFAULT NULL, current_rating DOUBLE PRECISION DEFAULT NULL, INDEX IDX_3EEF9B7C12469DE2 (category_id), INDEX IDX_3EEF9B7CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teas_tags (tea_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_878103B66A399A0D (tea_id), INDEX IDX_878103B6BAD26311 (tag_id), PRIMARY KEY(tea_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, plain_password VARCHAR(255) DEFAULT NULL, name VARCHAR(64) DEFAULT NULL, surname VARCHAR(64) DEFAULT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avatars ADD CONSTRAINT FK_B0C98520A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A6A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C9F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C96A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id)');
        $this->addSql('ALTER TABLE tealists ADD CONSTRAINT FK_991A9706F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tealists_teas ADD CONSTRAINT FK_7D2FD02FB65FE0AF FOREIGN KEY (tealist_id) REFERENCES tealists (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tealists_teas ADD CONSTRAINT FK_7D2FD02F6A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teas ADD CONSTRAINT FK_3EEF9B7C12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE teas ADD CONSTRAINT FK_3EEF9B7CF675F31B FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teas_tags ADD CONSTRAINT FK_878103B66A399A0D FOREIGN KEY (tea_id) REFERENCES teas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE teas_tags ADD CONSTRAINT FK_878103B6BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatars DROP FOREIGN KEY FK_B0C98520A76ED395');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A6A399A0D');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF675F31B');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C9F675F31B');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C96A399A0D');
        $this->addSql('ALTER TABLE tealists DROP FOREIGN KEY FK_991A9706F675F31B');
        $this->addSql('ALTER TABLE tealists_teas DROP FOREIGN KEY FK_7D2FD02FB65FE0AF');
        $this->addSql('ALTER TABLE tealists_teas DROP FOREIGN KEY FK_7D2FD02F6A399A0D');
        $this->addSql('ALTER TABLE teas DROP FOREIGN KEY FK_3EEF9B7C12469DE2');
        $this->addSql('ALTER TABLE teas DROP FOREIGN KEY FK_3EEF9B7CF675F31B');
        $this->addSql('ALTER TABLE teas_tags DROP FOREIGN KEY FK_878103B66A399A0D');
        $this->addSql('ALTER TABLE teas_tags DROP FOREIGN KEY FK_878103B6BAD26311');
        $this->addSql('DROP TABLE avatars');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE ratings');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tealists');
        $this->addSql('DROP TABLE tealists_teas');
        $this->addSql('DROP TABLE teas');
        $this->addSql('DROP TABLE teas_tags');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
