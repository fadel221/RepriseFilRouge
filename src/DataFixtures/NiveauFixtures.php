<?php

namespace App\DataFixtures;

use App\Entity\Niveau;
use App\Repository\CompetenceRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class NiveauFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=1;$i<=3;$i++)
        {
            $niveau=new Niveau();
            $niveau->setLibelle("Niveau ".$i);
            $niveau->setCritereEvaluation("CritereEvaluation ".$i);
            $niveau->setCriterPerformance("CriterPerformance".$i);
            $manager->persist($niveau);
            $manager->flush();
        }
        
    }
}
