<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230326200154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_album (category_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_D1AB210A12469DE2 (category_id), INDEX IDX_D1AB210A1137ABCF (album_id), PRIMARY KEY(category_id, album_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_album ADD CONSTRAINT FK_D1AB210A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_album ADD CONSTRAINT FK_D1AB210A1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE album DROP categories');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_album DROP FOREIGN KEY FK_D1AB210A12469DE2');
        $this->addSql('ALTER TABLE category_album DROP FOREIGN KEY FK_D1AB210A1137ABCF');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_album');
        $this->addSql('ALTER TABLE album ADD categories JSON NOT NULL');
    }
}
