<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613083513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        
        // Drop foreign keys referencing 'entrepot_stock' and drop 'entrepot_stock' table if it exists
        if ($schema->hasTable('entrepot_stock')) {
            $this->addSql('DROP TABLE entrepot_stock');
        }
        
        // Drop 'stock' table if it exists
        if ($schema->hasTable('stock')) {
            $this->addSql('DROP TABLE stock');
        }

        // Modify columns in other tables if necessary
        $this->addSql('ALTER TABLE client CHANGE roles roles VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE masse_produit masse_produit DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        
        // Recreate 'entrepot_stock' table if needed
        if (!$schema->hasTable('entrepot_stock')) {
            $this->addSql('CREATE TABLE entrepot_stock (id INT AUTO_INCREMENT NOT NULL, entrepot_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
        
        // Recreate 'stock' table if needed
        if (!$schema->hasTable('stock')) {
            $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, quantite INT NOT NULL, longueur DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
        
        // Add foreign keys only if they do not exist
        $this->addSql('ALTER TABLE barre ADD CONSTRAINT FK_D1EE71CBF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE entrepot_barre ADD CONSTRAINT FK_904769EE72831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
        $this->addSql('ALTER TABLE entrepot_barre ADD CONSTRAINT FK_904769EE8A2D3B88 FOREIGN KEY (barre_id) REFERENCES barre (id)');
        
        // Modify columns in other tables if necessary
        $this->addSql('ALTER TABLE client CHANGE roles roles VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE masse_produit masse_produit DOUBLE PRECISION DEFAULT NULL');
    }
}

