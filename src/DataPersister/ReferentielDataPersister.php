<?php


namespace App\DataPersister;

use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;


class ReferentielDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Referentiel;
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
       // $this->entityManager->persist($data);//Et on renvoie à la BD
        $this->entityManager->flush();
    }
}
