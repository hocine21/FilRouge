<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240621151729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop the table "categorie"';
    }

    public function up(Schema $schema): void
    {
        // Drop the foreign key constraint if it exists
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY IF EXISTS FK_29A5EC27132720D3');
        
        // Drop the table "categorie" if it exists and if no foreign key constraints prevent it
        $this->addSql('DROP TABLE IF EXISTS categorie');
    }

    public function down(Schema $schema): void
    {
        // Recreate the table "categorie" with its original structure if needed
        $this->addSql('CREATE TABLE IF NOT EXISTS categorie (
            id INT AUTO_INCREMENT NOT NULL,
            nom_categorie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Add back the foreign key constraint on 'ID_CATEGORIE' referencing 'categorie'
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27132720D3 FOREIGN KEY (ID_CATEGORIE) REFERENCES categorie (id)');
    }
}
