<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702181032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if (!$schema->getTable('entrepot_stock')->hasColumn('id')) {
            $this->addSql('CREATE TABLE entrepot_stock (id INT AUTO_INCREMENT NOT NULL, entrepot_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, INDEX IDX_A9F4E4572831E97 (entrepot_id), INDEX IDX_A9F4E45DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE entrepot_stock ADD CONSTRAINT FK_A9F4E4572831E97 FOREIGN KEY (entrepot_id) REFERENCES entrepot (id)');
            $this->addSql('ALTER TABLE entrepot_stock ADD CONSTRAINT FK_A9F4E45DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        }
        
        // Add other SQL statements as needed
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entrepot_stock DROP FOREIGN KEY FK_A9F4E4572831E97');
        $this->addSql('ALTER TABLE entrepot_stock DROP FOREIGN KEY FK_A9F4E45DCD6110');
        $this->addSql('DROP TABLE entrepot_stock');
        
        // Add other SQL statements as needed
    }
}
