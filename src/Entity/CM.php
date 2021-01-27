<?php

namespace App\Entity;

use App\Repository\CMRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
/**
 * @ORM\Entity(repositoryClass=CMRepository::class)
 */
class CM extends User
{
    

    public function getId(): ?int
    {
        return $this->id;
    }
}
