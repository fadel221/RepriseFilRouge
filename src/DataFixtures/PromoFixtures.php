<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Promo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PromoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1;$i<=3;$i++)
        {
            $promo= new Promo();
            $promo->setTitre("Promo ".$i);
            $promo->setLangue("FR");
            $promo->setLieu("Dakar");
            $promo->setDateDébut(new DateTime());
            $promo->setDescription("Promo ".$i);
            $promo->setFabrique("ODC");
            $promo->setReferentiel($this->getReference("Referentiel".$i));
            $this->addReference("Promo".$i,$promo);
            $manager->persist($promo);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ReferentielFixtures::class,
        );
    }
}
