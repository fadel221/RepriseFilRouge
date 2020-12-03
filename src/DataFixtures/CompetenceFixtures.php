<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Entity\Groupecompetence;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CompetenceFixtures extends Fixture implements DependentFixtureInterface {

    public function load(ObjectManager $manager)
    {
        for($i=1;$i<=3; $i++)
        {
            $competence=new Competence();
            $competence->setLibelle("Competence".$i);
            $this->addReference("Competence".$i,$competence);
            $Groupecompetence=new Groupecompetence();
            $Groupecompetence->setLibelle("Groupecompetence ".$i);
            $Groupecompetence->setDescriptif("Descriptif ".$i);
            $Groupecompetence->setType("Type ".$i);
            $Groupecompetence->setNom("Nom ".$i);
            //$this->addReference("Groupecompetence".$i, $Groupecompetence);
            $Groupecompetence->addCompetence($competence);
            $Groupecompetence->setUser($this->getReference('USER1'));
            //$manager->persist($Groupecompetence);
            $manager->persist($competence);
            $manager->persist($Groupecompetence);
        }

        $manager->flush();
    }

    //Précise de charger ProfilFixtures avec les UserFixtures
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}

