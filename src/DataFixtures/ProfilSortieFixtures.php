<?php

namespace App\DataFixtures;

use App\Entity\ProfilSortie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilSortieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tab=['Developpeur Fullstack','Designer UI/UX','Integrateur','Data Artisan'];
        foreach($tab as $data)
        {
            $profilsortie=new ProfilSortie();
            $profilsortie->setLibelle($data);
            $manager->persist($profilsortie);
            $manager->flush();
        }
        
    }
}
