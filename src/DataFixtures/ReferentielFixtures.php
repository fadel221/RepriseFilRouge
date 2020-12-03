<?php

namespace App\DataFixtures;

use App\Entity\Referentiel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\GroupecompetenceFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReferentielFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1;$i<=3;$i++)
        {
            $Referentiel = new Referentiel();
            $Referentiel -> setLibelle('Referentiel '.($i));
            $Referentiel -> setPresentation('presentation '.$i);
            $Referentiel -> setProgramme('programme '.$i);
            $Referentiel -> setCritereAdmission('critere Admission '.$i);
            $Referentiel -> setCritereEvaluation('critere Evaluation '.$i);
            $this->addReference("Referentiel".$i,$Referentiel);
            //$Referentiel -> addGroupecompetence($this->getReference('Groupecompetence1'));
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
