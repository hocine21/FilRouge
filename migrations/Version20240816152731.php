<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240816152731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE code_qr_commande code_qr_commande LONGBLOB NOT NULL');
        $this->addSql('ALTER TABLE livraison ADD code_postal VARCHAR(10) NOT NULL, ADD ville VARCHAR(255) NOT NULL, CHANGE adresse_livraison rue VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE code_qr_commande code_qr_commande LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE livraison ADD adresse_livraison VARCHAR(255) NOT NULL, DROP rue, DROP code_postal, DROP ville');
    }
}
