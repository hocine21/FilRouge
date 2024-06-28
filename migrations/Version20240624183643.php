<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240624183643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27CE19B47A');
        $this->addSql('DROP TABLE materiau');
        $this->addSql('DROP INDEX IDX_29A5EC27CE19B47A ON produit');
        $this->addSql('ALTER TABLE produit DROP materiau_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE materiau (id INT AUTO_INCREMENT NOT NULL, nom_materiau VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE produit ADD materiau_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27CE19B47A FOREIGN KEY (materiau_id) REFERENCES materiau (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27CE19B47A ON produit (materiau_id)');
    }
}
