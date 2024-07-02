<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702181123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    // Vérifier et supprimer la clé étrangère si elle existe
    if ($schema->getTable('produit')->hasForeignKey('FK_29A5EC27132720D3')) {
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27132720D3');
    }

    // Créer la table `fournisseur` si elle n'existe pas encore
    if (!$schema->hasTable('fournisseur')) {
        $this->addSql('CREATE TABLE fournisseur (
            id INT AUTO_INCREMENT NOT NULL,
            nom_fournisseur VARCHAR(255) NOT NULL,
            type_fourniture VARCHAR(255) NOT NULL,
            prix_htfournisseur DOUBLE PRECISION NOT NULL,
            email VARCHAR(255) DEFAULT NULL,
            telephone VARCHAR(20) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    // Créer la table `reponse` si elle n'existe pas encore
    if (!$schema->hasTable('reponse')) {
        $this->addSql('CREATE TABLE reponse (
            id INT AUTO_INCREMENT NOT NULL,
            id_employe INT NOT NULL,
            reponse_reclamation VARCHAR(255) NOT NULL,
            date_reponse DATE NOT NULL,
            INDEX IDX_5FB6DEC726997AC9 (id_employe),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        // Ajouter la contrainte FK si la table est créée
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC726997AC9 FOREIGN KEY (id_employe) REFERENCES employe (id)');
    }

    // Ajouter d'autres tables et contraintes SQL au besoin
}


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('fournisseur');
        $schema->dropTable('reponse');
        $schema->dropTable('stock');
        $schema->dropTable('reclamation');
        // Ajoutez d'autres instructions SQL au besoin
    }
}
