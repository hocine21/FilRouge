<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702180431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('client')) {
            $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, code_postale INT NOT NULL, adresse_email VARCHAR(255) NOT NULL, numero_telephone INT NOT NULL, ville VARCHAR(255) NOT NULL, nom_rue VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, siret INT DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, roles VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$schema->hasTable('commande')) {
            $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, date_commande DATETIME NOT NULL, code_qr_commande LONGBLOB NOT NULL, etat VARCHAR(255) NOT NULL, demande_devis TINYINT(1) NOT NULL, etat_devis VARCHAR(255) NOT NULL, ristourne DOUBLE PRECISION DEFAULT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        }

        // Add similar checks and SQL statements for other tables...
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        // Add SQL statements for dropping other tables...
    }
}
