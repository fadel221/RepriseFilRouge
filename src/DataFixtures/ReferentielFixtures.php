<?php

namespace App\DataFixtures;

use App\Entity\Referentiel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\GroupecompetenceFixtures;

class ReferentielFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1;$i<=3;$i++)
        {
            $Referentiel = new Referentiel();
            $Referentiel -> setLibelle('Referentiel '.$i);
            $Referentiel -> setPresentation('presentation '.$i);
            $Referentiel -> setProgramme('programme '.$i);
            $Referentiel -> setCritereAdmission('critere Admission '.$i);
            $Referentiel -> setCritereEvaluation('critere Evaluation '.$i);
            $Referentiel -> addGroupecompetence($this->getReference('Libelle '.$i));
        }
        $manager->persist($Referentiel);
        $manager->flush();
    }

    //Précise de charger ProfilFixtures avec les UserFixtures
    public function getDependencies()
    {
        return array(
            GroupecompetenceFixtures::class,
        );
    }
}
