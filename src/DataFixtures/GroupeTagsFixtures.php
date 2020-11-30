<?php

namespace App\DataFixtures;

use App\Entity\GroupeTags;
use App\DataFixtures\TagsFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GroupeTagsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i=0;$i<5;$i++)
        {
            $GroupeTags=new GroupeTags();
            $GroupeTags->setLibelle("GroupeTags ".($i+1));
            $GroupeTags->setIsDeleted(false);
            $GroupeTags ->addTag($this->getReference("Tags".($i+1)));
            $manager->persist($GroupeTags);
            
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            TagsFixtures::class,
        );
    }
}
