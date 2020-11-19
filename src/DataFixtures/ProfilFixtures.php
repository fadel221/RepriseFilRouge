<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    

    public function load(ObjectManager $manager)
    {
        $tab=['ADMIN','FORMATEUR','APPRENANT','CM'];
        foreach($tab as $value)
        {
            $profil=new Profil();
            $profil->setLibelle($value);
            $manager->persist($profil);
            $manager->flush();
            $this->addReference($value, $profil);
        }

        
    }
}
