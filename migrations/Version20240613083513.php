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
        if (!$schema->hasTable('entrepot_stock')) {
            $this->addSql('CREATE TABLE entrepot_stock (id INT AUTO_INCREMENT NOT NULL, entrepot_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
        if (!$schema->hasTable('stock')) {
            $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, quantite INT NOT NULL, longueur DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
        // Add foreign keys only if they do not exist
        $table = $schema->getTable('entrepot_stock');
        if ($table->hasForeignKey('FK_A9F4E4572831E97')) {
            $this->addSql('ALTER TABLE entrepot_stock DROP FOREIGN KEY FK_A9F4E4572831E97');
        }
        if ($table->hasForeignKey('FK_A9F4E45DCD6110')) {
            $this->addSql('ALTER TABLE entrepot_stock DROP FOREIGN KEY FK_A9F4E45DCD6110');
        }
        if ($schema->hasTable('barre')) {
            $this->addSql('DROP TABLE barre');
        }
        if ($schema->hasTable('entrepot_barre')) {
            if ($table->hasForeignKey('FK_904769EE72831E97')) {
                $this->addSql('ALTER TABLE entrepot_barre DROP FOREIGN KEY FK_904769EE72831E97');
            }
            $this->addSql('DROP TABLE entrepot_barre');
        }
        $this->addSql('ALTER TABLE client CHANGE roles roles VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE masse_produit masse_produit DOUBLE PRECISION NOT NULL');
    }
    
    
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE barre (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, quantite INT NOT NULL, longueur DOUBLE PRECISION NOT NULL, INDEX IDX_D1EE71CBF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE entrepot_barre (id INT AUTO_INCREMENT NOT NULL, entrepot_id INT DEFAULT NULL, barre_id INT DEFAULT NULL, INDEX IDX_904769EE72831E97 (entrepot_id), INDEX IDX_904769EE8A2D3B88 (barre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE barre ADD CONSTRAINT FK_D1EE71CBF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE entrepot_barre ADD CONSTRAINT FK_904769EE72831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
        $this->addSql('ALTER TABLE entrepot_barre ADD CONSTRAINT FK_904769EE8A2D3B88 FOREIGN KEY (barre_id) REFERENCES barre (id)');
        $this->addSql('ALTER TABLE entrepot_stock DROP FOREIGN KEY FK_A9F4E4572831E97');
        $this->addSql('ALTER TABLE entrepot_stock DROP FOREIGN KEY FK_A9F4E45DCD6110');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660F347EFB');
        $this->addSql('DROP TABLE entrepot_stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('ALTER TABLE client CHANGE roles roles VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE masse_produit masse_produit DOUBLE PRECISION DEFAULT NULL');
    }
}
