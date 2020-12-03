<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use App\DataFixtures\PromoFixtures;
use App\Repository\PromoRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GroupeFixtures extends Fixture implements DependentFixtureInterface
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
            $Groupe->setPromo($this->getReference("Promo".$i));
            $manager->persist($Groupe);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            PromoFixtures::class,
        );
    }
}
