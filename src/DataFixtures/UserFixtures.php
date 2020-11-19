<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
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
                $users = new User();
                $faker = Factory::create('fr_FR');
                $users->setNom($faker->lastname);
                $users->setPrenom($faker->firstname);
                $users->setEmail($faker->email);
                $users->setisDeleted(false);
                $users->setAvatar($faker->imageUrl());
                $users->setUsername(strtolower($faker->name()));
                $password = $this->encoder->encodePassword($users, "passe");
                $users->setPassword($password);
                $users->setProfil($this->getReference("ADMIN"));
                $manager->persist($users);
                $manager->flush();
        // other fixtures can get this object using the UserFixtures::ADMIN_USER_REFERENCE constant
        
    }
}
