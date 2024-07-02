<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240701084135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Créez la table 'categorie' si elle n'existe pas
        if (!$schema->hasTable('categorie')) {
            $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        // Créez la table 'materiau' si elle n'existe pas
        if (!$schema->hasTable('materiau')) {
            $this->addSql('CREATE TABLE materiau (id INT AUTO_INCREMENT NOT NULL, nom_materiau VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        // Autres modifications de la base de données
        $this->addSql('ALTER TABLE produit_fournisseur ADD etat_commande VARCHAR(50) NOT NULL, ADD etat_livraison VARCHAR(50) NOT NULL, ADD date_commande DATE NOT NULL, ADD date_livraison DATE NOT NULL, ADD quantite_commande INT NOT NULL, CHANGE fournisseur_id fournisseur_id INT NOT NULL, CHANGE produit_id produit_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Supprimez la table 'categorie' si elle existe
        if ($schema->hasTable('categorie')) {
            $this->addSql('DROP TABLE categorie');
        }

        // Supprimez la table 'materiau' si elle existe
        if ($schema->hasTable('materiau')) {
            $this->addSql('DROP TABLE materiau');
        }

        // Autres modifications de la base de données lors de la rétrogradation
        $this->addSql('ALTER TABLE produit_fournisseur DROP etat_commande, DROP etat_livraison, DROP date_commande, DROP date_livraison, DROP quantite_commande, CHANGE fournisseur_id fournisseur_id INT DEFAULT NULL, CHANGE produit_id produit_id INT DEFAULT NULL');
    }
}
