<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702181410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration to update schema with reclamation and related tables';
    }

    public function up(Schema $schema): void
    {
        // Create table 'reponse' if it does not exist
        if (!$schema->hasTable('reponse')) {
            $this->addSql('CREATE TABLE reponse (
                id INT AUTO_INCREMENT NOT NULL,
                id_employe INT NOT NULL,
                reponse_reclamation VARCHAR(255) NOT NULL,
                date_reponse DATE NOT NULL,
                INDEX IDX_5FB6DEC726997AC9 (id_employe),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC726997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id)');
        }

        // Drop table 'categorie' if it exists
        $this->addSql('DROP TABLE IF EXISTS categorie');

        // Add 'email' and 'telephone' columns to 'fournisseur' table if they do not exist
        $this->addSql('ALTER TABLE fournisseur 
            ADD COLUMN IF NOT EXISTS email VARCHAR(255) DEFAULT NULL, 
            ADD COLUMN IF NOT EXISTS telephone VARCHAR(20) DEFAULT NULL
        ');

        // Drop foreign key 'FK_29A5EC27132720D3' from 'produit' table if it exists
        if ($schema->getTable('produit')->hasForeignKey('FK_29A5EC27132720D3')) {
            $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27132720D3');
        }

        // Modify 'produit' table: drop 'ID_CATEGORIE' column, change 'marge' column type
        $this->addSql('ALTER TABLE produit 
            DROP COLUMN IF EXISTS ID_CATEGORIE,
            CHANGE marge marge DOUBLE PRECISION NOT NULL
        ');

        // Modify 'produit_fournisseur' table: add new columns, drop old columns, change column types
        $this->addSql('ALTER TABLE produit_fournisseur 
            ADD COLUMN IF NOT EXISTS etat_commande VARCHAR(50) NOT NULL, 
            ADD COLUMN IF NOT EXISTS etat_livraison VARCHAR(50) DEFAULT NULL, 
            ADD COLUMN IF NOT EXISTS date_commande DATE DEFAULT NULL, 
            ADD COLUMN IF NOT EXISTS date_livraison DATE DEFAULT NULL, 
            ADD COLUMN IF NOT EXISTS quantite_commande INT NOT NULL, 
            DROP COLUMN IF EXISTS date, 
            DROP COLUMN IF EXISTS quantite, 
            CHANGE produit_id produit_id INT NOT NULL, 
            CHANGE fournisseur_id fournisseur_id INT NOT NULL
        ');

        // Add foreign key 'FK_48868EB6670C757F' constraint to 'produit_fournisseur' table if it does not exist
        if (!$schema->getTable('produit_fournisseur')->hasForeignKey('FK_48868EB6670C757F')) {
            $this->addSql('ALTER TABLE produit_fournisseur 
                ADD CONSTRAINT FK_48868EB6670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)
            ');
        }

        // Modify 'reclamation' table: add new columns, drop old columns, change column types
        $this->addSql('ALTER TABLE reclamation 
            ADD COLUMN IF NOT EXISTS reponse_id INT DEFAULT NULL, 
            ADD COLUMN IF NOT EXISTS date_reclamation DATETIME NOT NULL, 
            ADD COLUMN IF NOT EXISTS description VARCHAR(255) NOT NULL
        ');

        // Add foreign key 'FK_CE606404CF18BB82' constraint to 'reclamation' table if it does not exist
        if (!$schema->getTable('reclamation')->hasForeignKey('FK_CE606404CF18BB82')) {
            $this->addSql('ALTER TABLE reclamation 
                ADD CONSTRAINT FK_CE606404CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id)
            ');
        }

        // Add index 'IDX_CE606404CF18BB82' to 'reclamation' table if it does not exist
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_CE606404CF18BB82 ON reclamation (reponse_id)');
    }

    public function down(Schema $schema): void
    {
        // Drop foreign key 'FK_CE606404CF18BB82' from 'reclamation' table if it exists
        if ($schema->getTable('reclamation')->hasForeignKey('FK_CE606404CF18BB82')) {
            $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404CF18BB82');
        }

        // Create 'categorie' table if it does not exist
        $this->addSql('CREATE TABLE IF NOT EXISTS categorie (
            id INT AUTO_INCREMENT NOT NULL, 
            nom_categorie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        // Drop foreign key 'FK_5FB6DEC726997AC9' from 'reponse' table if it exists
        if ($schema->getTable('reponse')->hasForeignKey('FK_5FB6DEC726997AC9')) {
            $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC726997AC9');
        }

        // Drop 'reponse' table if it exists
        $this->addSql('DROP TABLE IF EXISTS reponse');

        // Drop 'email' and 'telephone' columns from 'fournisseur' table if they exist
        $this->addSql('ALTER TABLE fournisseur 
            DROP COLUMN IF EXISTS email, 
            DROP COLUMN IF EXISTS telephone
        ');

        // Add 'ID_CATEGORIE' column back to 'produit' table, change 'marge' column type
        $this->addSql('ALTER TABLE produit 
            ADD COLUMN IF NOT EXISTS ID_CATEGORIE INT NOT NULL, 
            CHANGE marge marge DOUBLE PRECISION DEFAULT NULL
        ');

        // Add foreign key 'FK_29A5EC27132720D3' constraint to 'produit' table if it does not exist
        if (!$schema->getTable('produit')->hasForeignKey('FK_29A5EC27132720D3')) {
            $this->addSql('ALTER TABLE produit 
                ADD CONSTRAINT FK_29A5EC27132720D3 FOREIGN KEY (ID_CATEGORIE) REFERENCES categorie (id)
            ');
        }

        // Add index 'IDX_29A5EC27132720D3' to 'produit' table if it does not exist
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_29A5EC27132720D3 ON produit (ID_CATEGORIE)');

        // Drop foreign key 'FK_48868EB6670C757F' from 'produit_fournisseur' table if it exists
        if ($schema->getTable('produit_fournisseur')->hasForeignKey('FK_48868EB6670C757F')) {
            $this->addSql('ALTER TABLE produit_fournisseur DROP FOREIGN KEY FK_48868EB6670C757F');
        }

        // Modify 'produit_fournisseur' table: add new columns, drop old columns, change column types
        $this->addSql('ALTER TABLE produit_fournisseur 
            ADD COLUMN IF NOT EXISTS date DATETIME NOT NULL, 
            ADD COLUMN IF NOT EXISTS quantite VARCHAR(255) NOT NULL, 
            DROP COLUMN IF EXISTS etat_commande, 
            DROP COLUMN IF EXISTS etat_livraison, 
            DROP COLUMN IF EXISTS date_commande, 
            DROP COLUMN IF EXISTS date_livraison, 
            DROP COLUMN IF EXISTS quantite_commande, 
            CHANGE fournisseur_id fournisseur_id INT DEFAULT NULL, 
            CHANGE produit_id produit_id INT DEFAULT NULL
        ');

        // Drop index 'IDX_CE606404CF18BB82' from 'reclamation' table if it exists
        $this->addSql('DROP INDEX IF EXISTS IDX_CE606404CF18BB82 ON reclamation');

        // Modify 'reclamation' table: add 'etat' column back, drop 'reponse_id' and 'date_reclamation' columns, change column type
        $this->addSql('ALTER TABLE reclamation 
            ADD COLUMN IF NOT EXISTS etat TINYINT(1) NOT NULL, 
            DROP COLUMN IF EXISTS reponse_id, 
            DROP COLUMN IF EXISTS date_reclamation, 
            CHANGE message_reclamation description VARCHAR(255) NOT NULL
        ');
    }
}
