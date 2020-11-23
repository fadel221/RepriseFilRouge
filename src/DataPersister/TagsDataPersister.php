<?php


namespace App\DataPersister;




use App\Entity\Tags;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;

class TagsDataPersister implements DataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function supports($data): bool
    {
        return $data instanceof Tags;
    }
    /**
     * @param Tags $data
     */
    public function persist($data)
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    public function remove($data)
    {
        //Archivage du Tags
        $data->setisDeleted(true);//Mettre le statut à true pour montrer qu'on l'archive
        $this->entityManager->persist($data);//Et on renvoie à la BD
        $this->entityManager->flush();
        
    }
}
