<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702181232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        // Drop and recreate the table 'reponse' only if it doesn't exist
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

        // Drop the table 'categorie' only if it exists
        if ($schema->hasTable('categorie')) {
            $this->addSql('DROP TABLE categorie');
        }

        // Add 'email' and 'telephone' columns to 'fournisseur' only if they don't exist
        if (!$schema->getTable('fournisseur')->hasColumn('email')) {
            $this->addSql('ALTER TABLE fournisseur ADD email VARCHAR(255) DEFAULT NULL');
        }
        
        if (!$schema->getTable('fournisseur')->hasColumn('telephone')) {
            $this->addSql('ALTER TABLE fournisseur ADD telephone VARCHAR(20) DEFAULT NULL');
        }

        // Drop the index 'IDX_29A5EC27132720D3' on 'produit' only if it exists
        if ($schema->hasTable('produit') && $schema->getTable('produit')->hasIndex('IDX_29A5EC27132720D3')) {
            $this->addSql('DROP INDEX IDX_29A5EC27132720D3 ON produit');
        }

        // Drop the column 'ID_CATEGORIE' from 'produit' only if it exists
        if ($schema->hasTable('produit') && $schema->getTable('produit')->hasColumn('ID_CATEGORIE')) {
            $this->addSql('ALTER TABLE produit DROP COLUMN ID_CATEGORIE');
        }

        // Change the 'marge' column in 'produit' to DOUBLE PRECISION NOT NULL
        $this->addSql('ALTER TABLE produit CHANGE marge marge DOUBLE PRECISION NOT NULL');

        // Add columns to 'produit_fournisseur' only if they don't exist
        if (!$schema->getTable('produit_fournisseur')->hasColumn('etat_commande')) {
            $this->addSql('ALTER TABLE produit_fournisseur ADD etat_commande VARCHAR(50) NOT NULL');
        }
        
        if (!$schema->getTable('produit_fournisseur')->hasColumn('etat_livraison')) {
            $this->addSql('ALTER TABLE produit_fournisseur ADD etat_livraison VARCHAR(50) DEFAULT NULL');
        }
        
        if (!$schema->getTable('produit_fournisseur')->hasColumn('date_commande')) {
            $this->addSql('ALTER TABLE produit_fournisseur ADD date_commande DATE DEFAULT NULL');
        }
        
        if (!$schema->getTable('produit_fournisseur')->hasColumn('date_livraison')) {
            $this->addSql('ALTER TABLE produit_fournisseur ADD date_livraison DATE DEFAULT NULL');
        }
        
        if (!$schema->getTable('produit_fournisseur')->hasColumn('quantite_commande')) {
            $this->addSql('ALTER TABLE produit_fournisseur ADD quantite_commande INT NOT NULL');
        }

        // Change columns in 'produit_fournisseur' only if they exist
        if ($schema->getTable('produit_fournisseur')->hasColumn('date')) {
            $this->addSql('ALTER TABLE produit_fournisseur DROP date');
        }
        
        if ($schema->getTable('produit_fournisseur')->hasColumn('quantite')) {
            $this->addSql('ALTER TABLE produit_fournisseur DROP quantite');
        }
        
        $this->addSql('ALTER TABLE produit_fournisseur CHANGE produit_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit_fournisseur CHANGE fournisseur_id fournisseur_id INT NOT NULL');

        // Add 'reponse_id' and 'date_reclamation' columns to 'reclamation' only if they don't exist
        if (!$schema->getTable('reclamation')->hasColumn('reponse_id')) {
            $this->addSql('ALTER TABLE reclamation ADD reponse_id INT DEFAULT NULL');
        }
        
        if (!$schema->getTable('reclamation')->hasColumn('date_reclamation')) {
            $this->addSql('ALTER TABLE reclamation ADD date_reclamation DATETIME NOT NULL');
        }

        // Drop the 'etat' column and change 'description' to 'message_reclamation' in 'reclamation' only if they exist
        if ($schema->getTable('reclamation')->hasColumn('etat')) {
            $this->addSql('ALTER TABLE reclamation DROP etat');
        }
        
        if ($schema->getTable('reclamation')->hasColumn('description')) {
            $this->addSql('ALTER TABLE reclamation CHANGE description message_reclamation VARCHAR(255) NOT NULL');
        }

        // Add foreign key constraint to 'reclamation' only if it doesn't exist
        if (!$schema->getTable('reclamation')->hasForeignKey('FK_CE606404CF18BB82')) {
            $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id)');
        }

        // Add index to 'reclamation' only if it doesn't exist
        if (!$schema->hasTable('reclamation') || !$schema->getTable('reclamation')->hasIndex('IDX_CE606404CF18BB82')) {
            $this->addSql('CREATE INDEX IDX_CE606404CF18BB82 ON reclamation (reponse_id)');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        // Drop the foreign key 'FK_CE606404CF18BB82' from 'reclamation' only if it exists
        if ($schema->getTable('reclamation')->hasForeignKey('FK_CE606404CF18BB82')) {
            $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404CF18BB82');
        }

        // Drop the table 'reponse' only if it exists
        if ($schema->hasTable('reponse')) {
            $this->addSql('DROP TABLE reponse');
        }

        // Recreate the table 'categorie' only if it doesn't exist
        if (!$schema->hasTable('categorie')) {
            $this->addSql('CREATE TABLE categorie (
                id INT AUTO_INCREMENT NOT NULL,
                nom_categorie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        }

        // Drop 'email' and 'telephone' columns from 'fournisseur' only if they exist
        if ($schema->getTable('fournisseur')->hasColumn('email')) {
            $this->addSql('ALTER TABLE fournisseur DROP email');
        }
        
        if ($schema->getTable('fournisseur')->hasColumn('telephone')) {
            $this->addSql('ALTER TABLE fournisseur DROP telephone');
        }

        // Add 'ID_CATEGORIE' column back to 'produit' only if it doesn't exist
        if (!$schema->getTable('produit')->hasColumn('ID_CATEGORIE')) {
            $this->addSql('ALTER TABLE produit ADD ID_CATEGORIE INT NOT NULL');
        }

        // Change 'marge' column back to nullable in 'produit' only if it is not nullable
        $this->addSql('ALTER TABLE produit CHANGE marge marge DOUBLE PRECISION DEFAULT NULL');

        // Add foreign key 'FK_29A5EC27132720D3' to 'produit' only if it doesn't exist
        if (!$schema->getTable('produit')->hasForeignKey('FK_29A5EC27132720D3')) {
            $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27132720D3 FOREIGN KEY (ID_CATEGORIE) REFERENCES categorie (id)');
        }

        // Add index 'IDX_29A5EC27132720D3' to 'produit' only if it doesn't exist
        if (!$schema->hasTable('produit') || !$schema->getTable('produit')->hasIndex('IDX_29A5EC27132720D3')) {
            $this->addSql('CREATE INDEX IDX_29A5EC27132720D3 ON produit (ID_CATEGORIE)');
        }

        // Drop foreign key 'FK_48868EB6670C757F' from 'produit_fournisseur' only if it exists
        if ($schema->getTable('produit_fournisseur')->hasForeignKey('FK_48868EB6670C757F')) {
            $this->addSql('ALTER TABLE produit_fournisseur DROP FOREIGN KEY FK_48868EB6670C757F');
        }

        // Change columns 'produit_id' and 'fournisseur_id' back to nullable in 'produit_fournisseur' only if they are not nullable
        $this->addSql('ALTER TABLE produit_fournisseur CHANGE produit_id produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_fournisseur CHANGE fournisseur_id fournisseur_id INT DEFAULT NULL');

        // Add 'date' and 'quantite' columns back to 'produit_fournisseur' only if they don't exist
        if (!$schema->getTable('produit_fournisseur')->hasColumn('date')) {
            $this->addSql('ALTER TABLE produit_fournisseur ADD date DATETIME NOT NULL');
        }
        
        if (!$schema->getTable('produit_fournisseur')->hasColumn('quantite')) {
            $this->addSql('ALTER TABLE produit_fournisseur ADD quantite VARCHAR(255) NOT NULL');
        }

        // Drop index 'IDX_CE606404CF18BB82' from 'reclamation' only if it exists
        if ($schema->hasTable('reclamation') && $schema->getTable('reclamation')->hasIndex('IDX_CE606404CF18BB82')) {
            $this->addSql('DROP INDEX IDX_CE606404CF18BB82 ON reclamation');
        }

        // Recreate 'reponse_id' and 'date_reclamation' columns in 'reclamation' only if they don't exist
        if (!$schema->getTable('reclamation')->hasColumn('reponse_id')) {
            $this->addSql('ALTER TABLE reclamation DROP reponse_id');
        }
        
        if (!$schema->getTable('reclamation')->hasColumn('date_reclamation')) {
            $this->addSql('ALTER TABLE reclamation DROP date_reclamation');
        }

        // Change 'message_reclamation' back to 'description' and add 'etat' column back to 'reclamation' only if they don't exist
        if (!$schema->getTable('reclamation')->hasColumn('message_reclamation')) {
            $this->addSql('ALTER TABLE reclamation CHANGE message_reclamation description VARCHAR(255) NOT NULL');
        }
        
        $this->addSql('ALTER TABLE reclamation ADD etat TINYINT(1) NOT NULL');
    }
}
