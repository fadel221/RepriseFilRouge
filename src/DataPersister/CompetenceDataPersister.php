<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use App\Entity\Competence;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\Common\Collections\ArrayCollection;

class CompetenceDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
        if (isset($context['collection_operation_name']))
            $this->entityManager->persist($data);
        $this->entityManager->flush();
      // call your persistence layer to save $data
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
