<?php

namespace App\EventListener;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

class LoginListener
{
    private $em;

    public function __construct (EntityManagerInterface $em)
    {
        $this->em=$em;
    }

    public function onSecurityAuthenticationSuccess(AuthenticationEvent $event)
    {
        $user= $event->getAuthenticationToken()->getUser();
        $user->setLastUpdate(new DateTime());
        $this->em->persist($user);
        $this->em->flush();
    }
}