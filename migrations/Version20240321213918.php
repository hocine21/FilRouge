<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321213918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE roles roles VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE masse_produit masse_produit DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE produit_fournisseur ADD longueur_par_metre DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE roles roles VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE masse_produit masse_produit DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_fournisseur DROP longueur_par_metre');
    }
}
