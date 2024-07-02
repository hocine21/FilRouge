<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240702082533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix issue with produit_fournisseur table creation';
    }

    public function up(Schema $schema): void
    {
        // Drop the table produit_fournisseur if it exists
        $this->addSql('DROP TABLE IF EXISTS produit_fournisseur');

        // Recreate the table with correct structure
        $this->addSql('CREATE TABLE produit_fournisseur (
            id INT AUTO_INCREMENT NOT NULL,
            produit_id INT NOT NULL,
            fournisseur_id INT NOT NULL,
            PRIMARY KEY (id),
            UNIQUE INDEX UNIQ_48868EB66B3CA4B (produit_id, fournisseur_id),
            CONSTRAINT FK_48868EB66B3CA4B FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE,
            CONSTRAINT FK_48868EB6670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Drop the table produit_fournisseur if it exists
        $this->addSql('DROP TABLE IF EXISTS produit_fournisseur');
    }
}
