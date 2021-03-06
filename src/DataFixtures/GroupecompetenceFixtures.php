<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Groupecompetence;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\CompetenceFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GroupecompetenceFixtures extends Fixture implements DependentFixtureInterface
{
    
    public function load(ObjectManager $manager)
    {
        for($i=1;$i<=3; $i++)
        {
            $Groupecompetence=new Groupecompetence();
            $Groupecompetence->setLibelle("Groupecompetence ".$i);
            $Groupecompetence->setDescriptif("Descriptif ".$i);
            $Groupecompetence->setType("Type ".$i);
            $Groupecompetence->setNom("Nom ".$i);
            $this->addReference("Groupecompetence".$i, $Groupecompetence);
            $Groupecompetence->addCompetence($this->getReference("Competence1"));
            $Groupecompetence->setUser($this->getReference('USER1'));
            //$manager->persist($Groupecompetence);
        }

        //$manager->flush();
    }

    //Précise de charger ProfilFixtures avec les UserFixtures
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            CompetenceFixtures::class,
        );
    }
}
