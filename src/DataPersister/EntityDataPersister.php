<?php


namespace App\DataPersister;





use App\Entity\Tags;
use App\Entity\User;
use App\Entity\Niveau;
use App\Entity\Competence;
use App\Entity\GroupeTags;
use App\Entity\Groupecompetence;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\ProfilSortie;
use App\Entity\Referentiel;

class EntityDataPersister implements DataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function supports($data): bool
    {
        if ($data instanceof User ||$data instanceof Tags ||$data instanceof GroupeTags  || $data instanceof Groupecompetence ||$data instanceof Niveau || $data instanceof Referentiel || $data instanceof ProfilSortie)
        {
            return true;
        }
        return false;
    }
    
    public function persist($data)
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    public function remove($data)
    {
        $data->setisDeleted(true);//Mettre le statut à true pour montrer qu'on l'archive
       // $this->entityManager->persist($data);//Et on renvoie à la BD
        $this->entityManager->flush();
    }
}
