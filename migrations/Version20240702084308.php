<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702084308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_fournisseur ADD etat_commande VARCHAR(50) NOT NULL, ADD etat_livraison VARCHAR(50) NOT NULL, ADD date_commande DATE NOT NULL, ADD date_livraison DATE NOT NULL, ADD quantite_commande INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_fournisseur DROP etat_commande, DROP etat_livraison, DROP date_commande, DROP date_livraison, DROP quantite_commande');
    }
}
