<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240613104537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Drop the foreign key constraint on 'ID_CATEGORIE' in 'produit' table if it exists
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY IF EXISTS FK_29A5EC27132720D3');
        
        // Drop the foreign key constraint on 'id_materiau' in 'produit' table if it exists
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY IF EXISTS FK_29A5EC27CE19B47A');
    
        // Drop the index on 'id_materiau' in 'produit' table if it exists
        $this->addSql('DROP INDEX IF EXISTS IDX_29A5EC27CE19B47A ON produit');
    
        // Drop the column 'materiau_id' from 'produit' table if it exists
        $this->addSql('ALTER TABLE produit DROP COLUMN IF EXISTS materiau_id');
    
        // Add back the foreign key constraint on 'ID_CATEGORIE' referencing 'categorie'
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27132720D3 FOREIGN KEY (ID_CATEGORIE) REFERENCES categorie (id)');
    
        // Optionally, drop foreign key on 'produit_fournisseur' table if needed
        $this->addSql('ALTER TABLE produit_fournisseur DROP FOREIGN KEY IF EXISTS FK_48868EB6670C757F');
    }
    
    public function down(Schema $schema): void
    {
        // Drop the foreign key constraint on 'ID_CATEGORIE' in 'produit' table if it exists
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY IF EXISTS FK_29A5EC27132720D3');
        
        // Add the column 'materiau_id' back to 'produit' table
        $this->addSql('ALTER TABLE produit ADD materiau_id INT NOT NULL');
    
        // Add the foreign key constraint on 'id_materiau' referencing 'materiau'
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27CE19B47A FOREIGN KEY (materiau_id) REFERENCES materiau (id)');
    
        // Create an index on 'materiau_id' in 'produit' table
        $this->addSql('CREATE INDEX IDX_29A5EC27CE19B47A ON produit (materiau_id)');
    
        // Add back the foreign key constraint on 'ID_CATEGORIE' referencing 'categorie'
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27132720D3 FOREIGN KEY (ID_CATEGORIE) REFERENCES categorie (id)');
    
        // Optionally, add foreign key on 'produit_fournisseur' table if needed
        $this->addSql('ALTER TABLE produit_fournisseur ADD CONSTRAINT FK_48868EB6670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
    }
}
