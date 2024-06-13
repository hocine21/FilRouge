<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613103401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    // this up() migration is auto-generated, please modify it to your needs
    $table = $schema->getTable('produit_fournisseur');
    if ($table->hasForeignKey('FK_48868EB6670C757F')) {
        $this->addSql('ALTER TABLE produit_fournisseur DROP FOREIGN KEY FK_48868EB6670C757F');
    }
    if (!$schema->hasTable('fournisseur')) {
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom_fournisseur VARCHAR(255) NOT NULL, type_fourniture VARCHAR(255) NOT NULL, prix_htfournisseur DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
    if ($schema->hasTable('fournisseurs')) {
        $this->addSql('DROP TABLE fournisseurs');
    }
    $table = $schema->getTable('entrepot_stock');
    if (!$table->hasForeignKey('FK_A9F4E4572831E97')) {
        $this->addSql('ALTER TABLE entrepot_stock ADD CONSTRAINT FK_A9F4E4572831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
    }
    if (!$table->hasForeignKey('FK_A9F4E45DCD6110')) {
        $this->addSql('ALTER TABLE entrepot_stock ADD CONSTRAINT FK_A9F4E45DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
    }
    if ($schema->hasTable('barre')) {
        $this->addSql('DROP TABLE barre');
    }
    if ($schema->hasTable('entrepot_barre')) {
        $table = $schema->getTable('entrepot_barre');
        if ($table->hasForeignKey('FK_904769EE72831E97')) {
            $this->addSql('ALTER TABLE entrepot_barre DROP FOREIGN KEY FK_904769EE72831E97');
        }
        $this->addSql('DROP TABLE entrepot_barre');
    }
    $this->addSql('ALTER TABLE client CHANGE roles roles VARCHAR(255) NOT NULL');
    $this->addSql('ALTER TABLE produit CHANGE masse_produit masse_produit DOUBLE PRECISION NOT NULL');
    $table = $schema->getTable('produit');
    if ($table->hasForeignKey('FK_29A5EC27480E31C0')) {
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27480E31C0');
    }
}



    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27480E31C0');
        $this->addSql('DROP TABLE materiau');
        $this->addSql('DROP INDEX IDX_29A5EC27480E31C0 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE id_materiau categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
    }
}
