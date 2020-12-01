<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use App\Repository\PromoRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GroupeFixtures extends Fixture
{
    private $Promorep;

    public function __construct(PromoRepository $Promorep)
    {
        $this->Promorep=$Promorep;
    }

    public function load(ObjectManager $manager)
    {
        for ($i=1;$i<3;$i++)
        {
            $Groupe=new Groupe();
            $Groupe->setType("Secondaire");
            if ($i==1)
            {
                $Groupe->setType("Principal");
            }
            $Groupe->setPromo($this->Promorep->find(3));
            $manager->persist($Groupe);
        }

        $manager->flush();
    }
}
