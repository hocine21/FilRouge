<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701085206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Commentez ou supprimez toutes les références à la table 'categorie'
        // Exemple:
        // $this->addSql('INSERT INTO categorie (nom_categorie) VALUES (\'Exemple\')');
        // $this->addSql('UPDATE autre_table SET categorie_id = 1 WHERE ...');
    }

    public function down(Schema $schema): void
    {
        // Si nécessaire, implémentez la logique pour la méthode down
    }
}
