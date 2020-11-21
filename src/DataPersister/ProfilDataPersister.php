<?php

namespace App\DataPersister;




use App\Entity\Profil;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProfilDataPersister implements DataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function supports($data): bool
    {
        return $data instanceof Profil;
    }
    /**
     * @param Profil $data
     */
    public function persist($data)
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    public function remove($data)
    {
        //Archivage du profil
        $data->setisDeleted(true);//Mettre le statut à true pour montrer qu'on l'archive
        $users=$data->getUsers(); //Reccupérer tout les users avec ce profil
        foreach ($users as $user)
        {
            $user->setisDeleted(true); //Archiver chaque user
        }
        
        $this->entityManager->persist($data);//Et on renvoie à la BD
        $this->entityManager->flush();
        
    }
}
