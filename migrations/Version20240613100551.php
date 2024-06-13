<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240613100551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641082EA2E54');
        $this->addSql('DROP INDEX IDX_FE86641082EA2E54 ON facture');
        $this->addSql('ALTER TABLE facture ADD livraison VARCHAR(255) NOT NULL, DROP commande_id, DROP ville, DROP rue, DROP code_postlae, CHANGE montant montant_total DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD commande_id INT DEFAULT NULL, ADD rue VARCHAR(255) NOT NULL, ADD code_postlae INT NOT NULL, CHANGE montant_total montant DOUBLE PRECISION NOT NULL, CHANGE livraison ville VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_FE86641082EA2E54 ON facture (commande_id)');
    }
}
