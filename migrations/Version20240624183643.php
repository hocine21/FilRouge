<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240624183643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Check if foreign key exists before attempting to drop it
        if ($schema->getTable('produit')->hasForeignKey('FK_29A5EC27CE19B47A')) {
            $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27CE19B47A');
        }

        // Drop the materiau table if it exists
        if ($schema->hasTable('materiau')) {
            $this->addSql('DROP TABLE materiau');
        }

        // Drop the index on materiau_id if it exists
        $this->addSql('DROP INDEX IF EXISTS IDX_29A5EC27CE19B47A ON produit');

        // Drop the materiau_id column from produit table if it exists
        if ($schema->getTable('produit')->hasColumn('materiau_id')) {
            $this->addSql('ALTER TABLE produit DROP COLUMN materiau_id');
        }
    }

    public function down(Schema $schema): void
    {
        // Recreate the materiau table in the down migration
        $this->addSql('CREATE TABLE materiau (
            id INT AUTO_INCREMENT NOT NULL,
            nom_materiau VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Recreate the materiau_id column in produit table
        $this->addSql('ALTER TABLE produit ADD materiau_id INT NOT NULL');

        // Recreate the foreign key constraint on materiau_id
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27CE19B47A FOREIGN KEY (materiau_id) REFERENCES materiau (id)');

        // Recreate the index on materiau_id
        $this->addSql('CREATE INDEX IDX_29A5EC27CE19B47A ON produit (materiau_id)');
    }
}
