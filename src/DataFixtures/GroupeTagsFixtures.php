<?php

namespace App\DataFixtures;

use App\Entity\GroupeTags;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GroupeTagsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=1;$i<=4;$i++)
        {
            $GroupeTags=new GroupeTags();
            $GroupeTags->setLibelle("GroupeTag ".$i);
            $GroupeTags->setIsDeleted(false);
            $manager->persist($GroupeTags);
            $manager->flush();
        }
        
    }
}
