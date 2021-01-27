<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10,
 *          "normalization_context"={"groups"={"formateur_read"},"enable_max_depth"=true}
 *      },
 * 
 *      collectionOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="/formateurs",
 *              "defaults"={"id"=null}
 *          },
 *          "add_user"=
 *          {
 *              "method"="POST",
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="/formateurs",
 *              "defaults"={"id"=null}
 *          },
 *     },   
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"apprenant_read","apprenant_details_read"}},
 *              "path"="admin/formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null}
 *          },
 *          "get"={
 *              "security"="is_granted('ROLE_CM')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "normalization_context"={"groups"={"apprenant_read","apprenant_details_read"}},
 *              "path"="formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *              "defaults"={"id"=null}
 *          },
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *          
 *         "patch"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *         "put"={
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas ces privileges.",
 *              "path"="admin/formateurs/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *     },
 * )
 */
class Formateur extends User
{
    

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateur")
     */
    private $groupes;

    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, mappedBy="formateurs")
     */
    private $promos;

    public function __construct()
    {
        parent::__construct();
        $this->groupes = new ArrayCollection();
        $this->promos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeFormateur($this);
        }

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->addFormateur($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            $promo->removeFormateur($this);
        }

        return $this;
    }
}
