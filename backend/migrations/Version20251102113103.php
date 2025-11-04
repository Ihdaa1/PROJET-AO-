<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251102113103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appel_offre (id INT AUTO_INCREMENT NOT NULL, numero_ao VARCHAR(255) NOT NULL, date_publication DATE NOT NULL, objet VARCHAR(255) NOT NULL, entite VARCHAR(50) NOT NULL, responsable VARCHAR(50) NOT NULL, designation LONGTEXT DEFAULT NULL, unite VARCHAR(50) DEFAULT NULL, prix_ht NUMERIC(10, 2) DEFAULT NULL, quantite INT DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ao DROP FOREIGN KEY FK_E03234D09BEA957A');
        $this->addSql('ALTER TABLE offre_financiere DROP FOREIGN KEY FK_CEC2E51598355297');
        $this->addSql('ALTER TABLE offre_financiere DROP FOREIGN KEY FK_CEC2E515BE3DB2B7');
        $this->addSql('ALTER TABLE prix DROP FOREIGN KEY FK_F7EFEA5E2ACF0050');
        $this->addSql('ALTER TABLE prix DROP FOREIGN KEY FK_F7EFEA5EEC4A74AB');
        $this->addSql('DROP TABLE ao');
        $this->addSql('DROP TABLE entite');
        $this->addSql('DROP TABLE offre_financiere');
        $this->addSql('DROP TABLE prix');
        $this->addSql('DROP TABLE unite');
        $this->addSql('ALTER TABLE prestataire CHANGE email email VARCHAR(255) NOT NULL, CHANGE telephone telephone VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ao (id INT AUTO_INCREMENT NOT NULL, entite_id INT DEFAULT NULL, numero_ao VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_publication DATE DEFAULT NULL, objet VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E03234D09BEA957A (entite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE entite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, direction VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, responsable VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, abreviation VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offre_financiere (id INT AUTO_INCREMENT NOT NULL, ao_id INT NOT NULL, prestataire_id INT NOT NULL, total_ht NUMERIC(12, 2) DEFAULT NULL, INDEX IDX_CEC2E51598355297 (ao_id), INDEX IDX_CEC2E515BE3DB2B7 (prestataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prix (id INT AUTO_INCREMENT NOT NULL, offre_financiere_id INT NOT NULL, unite_id INT DEFAULT NULL, designation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prix_unitaire NUMERIC(12, 2) NOT NULL, quantite INT NOT NULL, montant_ht NUMERIC(12, 2) NOT NULL, INDEX IDX_F7EFEA5E2ACF0050 (offre_financiere_id), INDEX IDX_F7EFEA5EEC4A74AB (unite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE unite (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ao ADD CONSTRAINT FK_E03234D09BEA957A FOREIGN KEY (entite_id) REFERENCES entite (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE offre_financiere ADD CONSTRAINT FK_CEC2E51598355297 FOREIGN KEY (ao_id) REFERENCES ao (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre_financiere ADD CONSTRAINT FK_CEC2E515BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id)');
        $this->addSql('ALTER TABLE prix ADD CONSTRAINT FK_F7EFEA5E2ACF0050 FOREIGN KEY (offre_financiere_id) REFERENCES offre_financiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prix ADD CONSTRAINT FK_F7EFEA5EEC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('DROP TABLE appel_offre');
        $this->addSql('ALTER TABLE prestataire CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(50) DEFAULT NULL');
    }
}
