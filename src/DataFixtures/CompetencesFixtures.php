<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CompetenceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Competences=['Maquetter une apllication','Modéliser un projet','Intégrer une maquette','Responsivité'];
        foreach($Competences as $competence)
        {
            $Competence= new Competence();
            $Competence->setLibelle($competence);
            $Competence->setIsDeleted(false);
            $manager->persist($Competence);
            $manager->flush();
        }
        
    }
}
