<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124091505 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupes (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, date_creation DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupecompetence ADD statut TINYINT(1) NOT NULL, ADD type VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD date_creation DATE NOT NULL');
        $this->addSql('ALTER TABLE profil ADD last_update DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD last_update DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE groupes');
        $this->addSql('ALTER TABLE groupecompetence DROP statut, DROP type, DROP nom, DROP date_creation');
        $this->addSql('ALTER TABLE profil DROP last_update');
        $this->addSql('ALTER TABLE `user` DROP last_update');
    }
}
