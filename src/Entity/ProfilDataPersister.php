<?php

namespace App\Entity;

use App\Repository\ProfilDataPersisterRepository;


use App\Entity\Profil;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class ProfilDataPersister implements DataPersisterInterface
{
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
        $data->setisDeleted(true);
        $this->entityManager->$this->persist($data);
        $this->entityManager->flush();
    }
}
