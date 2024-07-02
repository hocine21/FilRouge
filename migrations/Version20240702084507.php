<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240702084507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove date column from produit table if it exists';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('produit');

        // Check if the 'date' column exists before trying to drop it
        if ($table->hasColumn('date')) {
            $this->addSql('ALTER TABLE produit DROP COLUMN `date`');
        }
    }

    public function down(Schema $schema): void
    {
        // This is a one-way migration, so down() method is not implemented
        // If you need a reverse migration, implement it accordingly
        $this->throwIrreversibleMigrationException('Cannot reverse dropping of column `date`');
    }
}
