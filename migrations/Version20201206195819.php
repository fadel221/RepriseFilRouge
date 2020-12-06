<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206195819 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable_attendus (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrables (id INT AUTO_INCREMENT NOT NULL, url LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brief ADD langue VARCHAR(255) NOT NULL, ADD titre VARCHAR(255) NOT NULL, ADD contexte LONGTEXT NOT NULL, ADD modalite_pedagogiques LONGTEXT NOT NULL, ADD criteres_performance LONGTEXT NOT NULL, ADD is_deleted TINYINT(1) NOT NULL, ADD date_creation DATE NOT NULL');
        $this->addSql('ALTER TABLE referentiel CHANGE presentation presentation LONGBLOB NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE livrable_attendus');
        $this->addSql('DROP TABLE livrables');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('ALTER TABLE brief DROP langue, DROP titre, DROP contexte, DROP modalite_pedagogiques, DROP criteres_performance, DROP is_deleted, DROP date_creation');
        $this->addSql('ALTER TABLE referentiel CHANGE presentation presentation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
