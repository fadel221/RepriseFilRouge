<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127112727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_89DC1A55A4D60759 ON groupecompetence (libelle)');
        $this->addSql('ALTER TABLE niveau ADD is_deleted TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B76C2029A4D60759 ON referentiel (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FBC9426A4D60759 ON tags (libelle)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_89DC1A55A4D60759 ON groupecompetence');
        $this->addSql('ALTER TABLE niveau DROP is_deleted');
        $this->addSql('DROP INDEX UNIQ_B76C2029A4D60759 ON referentiel');
        $this->addSql('DROP INDEX UNIQ_6FBC9426A4D60759 ON tags');
    }
}
