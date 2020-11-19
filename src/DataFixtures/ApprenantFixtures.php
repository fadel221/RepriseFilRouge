<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Profil;
use App\Entity\Apprenant;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users=new Apprenant();
        $faker = Factory::create('fr_FR');
                $users->setNom($faker->lastname);
                $users->setPrenom($faker->firstname);
                $users->setEmail($faker->email);
                $users->setisDeleted(false);
                $users->setAvatar($faker->imageUrl());
                $users->setUsername(strtolower($faker->name()));
                $password = $this->encoder->encodePassword($users, "passe");
                $users->setPassword($password);
                $users->setProfil($this->getReference("APPRENANT"));
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
