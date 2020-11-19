<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Profil;
use App\Entity\Formateur;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormateurFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $users=new Formateur();
        $faker = Factory::create('fr_FR');
                $users->setNom($faker->lastname);
                $users->setPrenom($faker->firstname);
                $users->setEmail($faker->email);
                $users->setisDeleted(false);
                $users->setAvatar($faker->imageUrl());
                $users->setUsername(strtolower($faker->name()));
                $password = $this->encoder->encodePassword($users, "passe");
                $users->setPassword($password);
                $users->setProfil($this->getReference("FORMATEUR"));
                $manager->persist($users);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
