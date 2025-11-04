<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104122802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marche (id INT AUTO_INCREMENT NOT NULL, ref_ao_id INT NOT NULL, ref_prestataire_id INT NOT NULL, montant_ht NUMERIC(15, 2) NOT NULL, numero VARCHAR(100) NOT NULL, date_signature DATE NOT NULL, INDEX IDX_BAA18ACCEBFE3755 (ref_ao_id), INDEX IDX_BAA18ACCF3AB83B8 (ref_prestataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE marche ADD CONSTRAINT FK_BAA18ACCEBFE3755 FOREIGN KEY (ref_ao_id) REFERENCES appel_offre (id)');
        $this->addSql('ALTER TABLE marche ADD CONSTRAINT FK_BAA18ACCF3AB83B8 FOREIGN KEY (ref_prestataire_id) REFERENCES prestataire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marche DROP FOREIGN KEY FK_BAA18ACCEBFE3755');
        $this->addSql('ALTER TABLE marche DROP FOREIGN KEY FK_BAA18ACCF3AB83B8');
        $this->addSql('DROP TABLE marche');
    }
}
