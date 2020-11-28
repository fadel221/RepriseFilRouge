<?php
namespace App\DataPersister;



use App\Entity\User;
use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\Common\Collections\ArrayCollection;

class TagsDataPersister implements DataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @param Tags data
     */
    public function supports($data): bool
    {
        return $data instanceof Tags;
    }
    
    public function persist($data)
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    public function remove($data)
    {
        $data->setisDeleted(true);//Mettre le statut à true pour montrer qu'on l'archive
        //Suppression du lien entre le Tags et le grpe de Tags
        $data->RemoveAllGroupeTags();
        $this->entityManager->persist($data);//Et on renvoie à la BD
        $this->entityManager->flush();
    }
}
