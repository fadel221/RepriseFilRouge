<?php
namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Groupecompetence;
use App\Repository\GroupecompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class GroupecompetenceDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private $Groupecompetencerepository;
    
    public function __construct(EntityManagerInterface $entityManager,GroupecompetenceRepository $Groupecompetencerepository)
    {
        $this->entityManager = $entityManager;
        $this->Groupecompetencerepository=$Groupecompetencerepository;
    }
    /**
     * @param Groupecompetence $data
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Groupecompetence;
    }

    public function persist($data, array $context = [])
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
      return $data;
    }

    public function remove($data,array $context = [])
    {
        $data->setisDeleted(true);//Mettre le statut à true pour montrer qu'on l'archive
        //Suppression du lien entre la Groupecompetence et le grpe de Groupecompetences de meme que les referentiels
        $data->RemoveAllCompetences();
        $data->RemoveAllReferentiels();
        $this->entityManager->persist($data);//Et on renvoie à la BD
        $this->entityManager->flush();
    }
}
