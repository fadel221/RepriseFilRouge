<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TagsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tags=['PHP/MySQL','JQUERY','HTML/CSS','Symfony','Angular'];
        foreach($tags as $tag)
        {
            $Tags=new Tags();
            $Tags->setLibelle($tag);
            $Tags->setDescriptif("Voici le descriptif de ".$tag);
            $Tags->setIsDeleted(false);
            $manager->persist($Tags);
            $manager->flush();
        }
        
    }
}
