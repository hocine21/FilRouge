<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240816152758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update columns for livraison table';
    }

    public function up(Schema $schema): void
    {
        // Update `commande` table
        $this->addSql('ALTER TABLE commande CHANGE code_qr_commande code_qr_commande LONGBLOB NOT NULL');
        
        // Check if the columns exist in the `livraison` table
        $columns = $this->getExistingColumns('livraison');

        // Add `code_postal` if it doesn't exist
        if (!in_array('code_postal', $columns, true)) {
            $this->addSql('ALTER TABLE livraison ADD code_postal VARCHAR(10) NOT NULL');
        }

        // Add `ville` if it doesn't exist
        if (!in_array('ville', $columns, true)) {
            $this->addSql('ALTER TABLE livraison ADD ville VARCHAR(255) NOT NULL');
        }

        // Change `adresse_livraison` to `rue` if `adresse_livraison` exists, otherwise add `rue`
        if (in_array('adresse_livraison', $columns, true)) {
            $this->addSql('ALTER TABLE livraison CHANGE adresse_livraison rue VARCHAR(255) NOT NULL');
        } elseif (!in_array('rue', $columns, true)) {
            $this->addSql('ALTER TABLE livraison ADD rue VARCHAR(255) NOT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // Revert changes to `commande` table
        $this->addSql('ALTER TABLE commande CHANGE code_qr_commande code_qr_commande LONGBLOB DEFAULT NULL');
        
        // Check if the columns exist in the `livraison` table
        $columns = $this->getExistingColumns('livraison');

        // Drop `rue` and add `adresse_livraison` back if `rue` exists
        if (in_array('rue', $columns, true)) {
            $this->addSql('ALTER TABLE livraison ADD adresse_livraison VARCHAR(255) NOT NULL');
            $this->addSql('ALTER TABLE livraison DROP rue');
        }

        // Drop `code_postal` if it exists
        if (in_array('code_postal', $columns, true)) {
            $this->addSql('ALTER TABLE livraison DROP code_postal');
        }

        // Drop `ville` if it exists
        if (in_array('ville', $columns, true)) {
            $this->addSql('ALTER TABLE livraison DROP ville');
        }
    }

    /**
     * Gets the list of existing columns in a table.
     *
     * @param string $tableName
     * @return array
     */
    private function getExistingColumns(string $tableName): array
    {
        $columns = [];
        $stmt = $this->connection->executeQuery('DESCRIBE ' . $tableName);
        while ($row = $stmt->fetchAssociative()) {
            $columns[] = $row['Field'];
        }
        return $columns;
    }
}
