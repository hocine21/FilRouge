<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240708155741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE materiau');
        $this->addSql('ALTER TABLE produit_fournisseur CHANGE etat_livraison etat_livraison VARCHAR(50) DEFAULT NULL, CHANGE date_commande date_commande DATE DEFAULT NULL, CHANGE date_livraison date_livraison DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_4B365660F347EFB ON stock (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE materiau (id INT AUTO_INCREMENT NOT NULL, nom_materiau VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE produit_fournisseur CHANGE etat_livraison etat_livraison VARCHAR(50) NOT NULL, CHANGE date_commande date_commande DATE NOT NULL, CHANGE date_livraison date_livraison DATE NOT NULL');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660F347EFB');
        $this->addSql('DROP INDEX IDX_4B365660F347EFB ON stock');
    }
}
