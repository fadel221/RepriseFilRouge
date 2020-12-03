<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TagsFixtures extends Fixture 
{
    public function load(ObjectManager $manager)
    {
        $tags=['PHP/SQL','JQUER','HTML/CSS/BOOTSTRAP','CMS','WORDPRESS'];
        for($i=0;$i<5 ;$i++)
        {
            $Tags=new Tags();
            $Tags->setLibelle($tags[$i]);
            $Tags->setDescriptif("Voici le descriptif de ".($i+1));
            $Tags->setIsDeleted(false);
            $this->addReference("Tags".($i+1),$Tags);
            $manager->persist($Tags);
            
        }

        $manager->flush();
        
    }
}
