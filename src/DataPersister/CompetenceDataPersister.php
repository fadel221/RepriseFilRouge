<?php
namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Competence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class CompetenceDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private $competencerepository;
    
    public function __construct(EntityManagerInterface $entityManager,CompetenceRepository $competencerepository)
    {
        $this->entityManager = $entityManager;
        $this->competencerepository=$competencerepository;
    }
    /**
     * @param Competence $data
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Competence;
    }

    public function persist($data, array $context = [])
    {
        dd($data);
        if (isset($context['collection_operation_name']))
        
            $this->entityManager->persist($data);
        $this->entityManager->flush();
      return $data;
    }

    public function remove($data,array $context = [])
    {
        $data->setisDeleted(true);//Mettre le statut à true pour montrer qu'on l'archive
        //Suppression du lien entre la competence et le grpe de competences
        $data->RemoveAllGroupecompetences();
        $niveaux=$data->getNiveau();
        foreach ($niveaux as $niveau)
        {
            //Archivage du Niveau de la competence
            $niveau->setisDeleted(true);
            $this->entityManager->persist($niveau);
            $this->entityManager->flush();
        }
        $this->entityManager->persist($data);//Et on renvoie à la BD
        $this->entityManager->flush();
    }
}
