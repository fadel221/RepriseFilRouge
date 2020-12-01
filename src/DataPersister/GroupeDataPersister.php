<?php


namespace App\DataPersister;


use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Repository\GroupeRepository;

class GroupeDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function supports($data,array $contex=[]): bool
    {
        return ($data instanceof Groupe);
    }
    
    public function persist($data,array $contex=[])
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    
    public function remove($data,array $contex=[])
    {
        $data->setisDeleted(true);//Mettre le statut à true pour montrer qu'on l'archive
        $this->entityManager->flush();
    }
}
