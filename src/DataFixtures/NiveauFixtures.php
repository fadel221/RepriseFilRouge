<?php

namespace App\DataFixtures;

use App\Entity\Niveau;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\CompetenceFixtures;
use App\Repository\CompetenceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NiveauFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i=1;$i<=3;$i++)
        {
            $niveau=new Niveau();
            $niveau->setLibelle("Niveau ".$i);
            $niveau->setCritereEvaluation("CritereEvaluation ".$i);
            $niveau->setCriterePerformance("CriterPerformance" .$i);
            $niveau->setGroupeAction("GroupeAction ".$i);
            $niveau->setCompetence($this->getReference("Competence1"));
            $manager->persist($niveau);
            $manager->flush();
        }
        
    }

    public function getDependencies()
    {
        return array(
            CompetenceFixtures::class,
        );
    }
}
