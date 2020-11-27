<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201127083955 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94D4687FA4D60759 ON competence (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C754EB91A4D60759 ON groupe_tags (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_89DC1A55A4D60759 ON groupecompetence (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B76C2029A4D60759 ON referentiel (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6FBC9426A4D60759 ON tags (libelle)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_94D4687FA4D60759 ON competence');
        $this->addSql('DROP INDEX UNIQ_C754EB91A4D60759 ON groupe_tags');
        $this->addSql('DROP INDEX UNIQ_89DC1A55A4D60759 ON groupecompetence');
        $this->addSql('DROP INDEX UNIQ_B76C2029A4D60759 ON referentiel');
        $this->addSql('DROP INDEX UNIQ_6FBC9426A4D60759 ON tags');
    }
}
