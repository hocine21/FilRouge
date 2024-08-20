<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240816160834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD adresse_livraison VARCHAR(255) DEFAULT NULL, ADD ville_livraison VARCHAR(255) DEFAULT NULL, ADD code_postal_livraison VARCHAR(10) DEFAULT NULL, ADD adresse_facturation VARCHAR(255) DEFAULT NULL, ADD ville_facturation VARCHAR(255) DEFAULT NULL, ADD code_postal_facturation VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP adresse_livraison, DROP ville_livraison, DROP code_postal_livraison, DROP adresse_facturation, DROP ville_facturation, DROP code_postal_facturation');
    }
}
