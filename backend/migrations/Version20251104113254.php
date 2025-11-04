<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104113254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, direction VARCHAR(255) DEFAULT NULL, responsable VARCHAR(255) DEFAULT NULL, abreviation VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_financiere (id INT AUTO_INCREMENT NOT NULL, ref_ao_id INT NOT NULL, ref_prestataire_id INT NOT NULL, total_ht NUMERIC(15, 2) NOT NULL, INDEX IDX_CEC2E515EBFE3755 (ref_ao_id), INDEX IDX_CEC2E515F3AB83B8 (ref_prestataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prix (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, unite_id INT NOT NULL, designation VARCHAR(255) NOT NULL, prix_unitaire NUMERIC(15, 2) NOT NULL, quantite INT NOT NULL, montant_ht NUMERIC(15, 2) NOT NULL, INDEX IDX_F7EFEA5E4CC8505A (offre_id), INDEX IDX_F7EFEA5EEC4A74AB (unite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offre_financiere ADD CONSTRAINT FK_CEC2E515EBFE3755 FOREIGN KEY (ref_ao_id) REFERENCES appel_offre (id)');
        $this->addSql('ALTER TABLE offre_financiere ADD CONSTRAINT FK_CEC2E515F3AB83B8 FOREIGN KEY (ref_prestataire_id) REFERENCES prestataire (id)');
        $this->addSql('ALTER TABLE prix ADD CONSTRAINT FK_F7EFEA5E4CC8505A FOREIGN KEY (offre_id) REFERENCES offre_financiere (id)');
        $this->addSql('ALTER TABLE prix ADD CONSTRAINT FK_F7EFEA5EEC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('ALTER TABLE appel_offre ADD entite_entity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appel_offre ADD CONSTRAINT FK_BC56FD4753B79E7D FOREIGN KEY (entite_entity_id) REFERENCES entite (id)');
        $this->addSql('CREATE INDEX IDX_BC56FD4753B79E7D ON appel_offre (entite_entity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_offre DROP FOREIGN KEY FK_BC56FD4753B79E7D');
        $this->addSql('ALTER TABLE offre_financiere DROP FOREIGN KEY FK_CEC2E515EBFE3755');
        $this->addSql('ALTER TABLE offre_financiere DROP FOREIGN KEY FK_CEC2E515F3AB83B8');
        $this->addSql('ALTER TABLE prix DROP FOREIGN KEY FK_F7EFEA5E4CC8505A');
        $this->addSql('ALTER TABLE prix DROP FOREIGN KEY FK_F7EFEA5EEC4A74AB');
        $this->addSql('DROP TABLE entite');
        $this->addSql('DROP TABLE offre_financiere');
        $this->addSql('DROP TABLE prix');
        $this->addSql('DROP TABLE unite');
        $this->addSql('DROP INDEX IDX_BC56FD4753B79E7D ON appel_offre');
        $this->addSql('ALTER TABLE appel_offre DROP entite_entity_id');
    }
}
