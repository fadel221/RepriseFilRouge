<?php

namespace App\DataFixtures;

use App\Entity\CM;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $class=[new User(),new Formateur(),new Apprenant(),new CM ()];
        $ref=['ADMIN','FORMATEUR','APPRENANT','CM'];
        for ($i=0;$i<4 ;$i++)
        {
                $users = $class[$i];
                $faker = Factory::create('fr_FR');
                $users->setNom($faker->lastname);
                $users->setPrenom($faker->firstname);
                $users->setEmail($faker->email);
                $users->setisDeleted(false);
                $users->setAvatar($faker->imageUrl());
                $users->setUsername(strtolower($faker->name()));
                $password = $this->encoder->encodePassword($users, "passe");
                $users->setPassword($password);
               // $this->addReference('USER'.($i+1),$users);
                $users->setProfil($this->getReference(($ref[$i])));
                $manager->persist($users);
                $manager->flush();
        }
        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        
    }
    //Précise de charger ProfilFixtures avec les UserFixtures
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }

    
       
        
    
}
