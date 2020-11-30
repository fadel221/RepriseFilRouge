<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Groupecompetence;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GroupecompetenceFixtures extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        for($i=1;$i<=3; $i++)
        {
            $Groupecompetence=new Groupecompetence();
            $Groupecompetence->setLibelle("Groupecompetence ".($i+10));
            $Groupecompetence->setDescriptif("Descriptif ".$i);
            $Groupecompetence->setType("Type ".$i);
            $Groupecompetence->setNom("Nom ".$i);
            $this->addReference("Groupecompetence ".$i, $Groupecompetence);
            $Groupecompetence->setUser($this->getReference('USER1'));
            $manager->persist($Groupecompetence);
            $manager->flush();
        }
    }

    //Précise de charger ProfilFixtures avec les UserFixtures
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
