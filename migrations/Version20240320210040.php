<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240320210040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop barre table and its foreign key constraints';
    }

    public function up(Schema $schema): void
    {
        // Supprimer les contraintes de clé étrangère qui référencent la table barre
        $this->addSql('ALTER TABLE entrepot_barre DROP FOREIGN KEY FK_904769EE72831E97');
        $this->addSql('ALTER TABLE entrepot_barre DROP FOREIGN KEY FK_904769EE8A2D3B88');

        // Supprimer la table barre une fois les contraintes de clé étrangère supprimées
        $this->addSql('DROP TABLE IF EXISTS barre');
    }

    public function down(Schema $schema): void
    {
        // Recréer les contraintes de clé étrangère si nécessaire
        $this->addSql('ALTER TABLE entrepot_barre ADD CONSTRAINT FK_904769EE72831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
        $this->addSql('ALTER TABLE entrepot_barre ADD CONSTRAINT FK_904769EE8A2D3B88 FOREIGN KEY (barre_id) REFERENCES barre (id)');

        // Recréer la table barre lors de la réversion, si nécessaire
        $this->addSql('CREATE TABLE barre (
            id INT AUTO_INCREMENT NOT NULL,
            produit_id INT DEFAULT NULL,
            quantite INT NOT NULL,
            longueur DOUBLE PRECISION NOT NULL,
            INDEX IDX_D1EE71CBF347EFB (produit_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
}
